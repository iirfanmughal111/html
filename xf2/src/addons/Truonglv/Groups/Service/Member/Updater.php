<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Member;

use Truonglv\Groups\App;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Member;
use XF\Service\ValidateAndSavableTrait;

class Updater extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var Member
     */
    protected $member;

    public function __construct(\XF\App $app, Member $member)
    {
        parent::__construct($app);

        $this->member = $member;
    }

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->member;
    }
    /**
     * @param bool $email
     * @param bool $alert
     * @return $this
     */
    public function setAlertVia($email = true, $alert = true)
    {
        if ($email && $alert) {
            $this->member->alert = App::MEMBER_ALERT_OPT_ALL;
        } elseif ($email) {
            $this->member->alert = App::MEMBER_ALERT_OPT_EMAIL_ONLY;
        } elseif ($alert) {
            $this->member->alert = App::MEMBER_ALERT_OPT_ALERT_ONLY;
        } else {
            $this->member->alert = App::MEMBER_ALERT_OPT_OFF;
        }

        return $this;
    }

    /**
     * @return bool
     * @deprecated
     * @throws \XF\PrintableException
     */
    public function update()
    {
        $this->_save();

        return true;
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->member->preSave();

        return $this->member->getErrors();
    }

    /**
     * @return Member
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $db->beginTransaction();

        $this->member->save(true, false);

        $db->commit();

        return $this->member;
    }
}
