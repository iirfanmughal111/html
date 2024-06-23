<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Group;

use XF;
use LogicException;
use Truonglv\Groups\App;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;

class Approver extends AbstractService
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * @var bool
     */
    protected $alertApproved = true;

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        if (!$group->exists()) {
            throw new LogicException('Group must be exists');
        }
        $this->group = $group;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setAlertApproved(bool $value)
    {
        $this->alertApproved = $value;
    }

    /**
     * @return void
     */
    public function toggle()
    {
        if ($this->group->isVisible()) {
            $this->unapprove();
        } else {
            $this->approve();
        }
    }

    /**
     * @return bool
     * @throws \XF\PrintableException
     */
    public function approve()
    {
        if ($this->group->group_state === App::STATE_MODERATED) {
            $this->group->group_state = App::STATE_VISIBLE;
            $this->group->save();

            if ($this->alertApproved && $this->group->owner_user_id != XF::visitor()->user_id) {
                $this->onApprove();
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * @throws \XF\PrintableException
     */
    public function unapprove()
    {
        if ($this->group->group_state === App::STATE_VISIBLE) {
            $this->group->group_state = App::STATE_MODERATED;
            $this->group->save();

            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    protected function onApprove()
    {
        if ($this->group->User !== null) {
            $visitor = XF::visitor();

            App::alert(
                $this->group->User,
                $visitor->user_id,
                $visitor->username,
                App::CONTENT_TYPE_GROUP,
                $this->group->group_id,
                'approved'
            );
        }
    }
}
