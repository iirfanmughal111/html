<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Member;

use XF;
use LogicException;
use Truonglv\Groups\App;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Member;
use Truonglv\Groups\Entity\MemberRole;

class Promoter extends AbstractService
{
    /**
     * @var Member
     */
    protected $member;
    /**
     * @var string
     */
    protected $memberRoleId;
    /**
     * @var string
     */
    protected $alertAction;

    /**
     * @var bool
     */
    protected $logAction = true;

    public function __construct(\XF\App $app, Member $member)
    {
        parent::__construct($app);

        if (!$member->exists()) {
            throw new LogicException('Member must be exists.');
        }

        $this->member = $member;
    }

    /**
     * @param string $memberRoleId
     * @return $this|Promoter
     */
    public function setMemberRoleId($memberRoleId)
    {
        if ($memberRoleId === App::MEMBER_ROLE_ID_ADMIN) {
            return $this->makeAdmin();
        } elseif ($memberRoleId === App::MEMBER_ROLE_ID_MODERATOR) {
            return $this->makeModerator();
        }

        $this->memberRoleId = $memberRoleId;
        $this->alertAction = 'promoted';

        return $this;
    }

    /**
     * @param bool $logAction
     * @return void
     */
    public function setLogAction(bool $logAction)
    {
        $this->logAction = $logAction;
    }

    /**
     * @return $this
     */
    public function makeAdmin()
    {
        $this->memberRoleId = App::MEMBER_ROLE_ID_ADMIN;
        $this->alertAction = 'promoted_admin';

        return $this;
    }

    /**
     * @return $this
     */
    public function removeAdmin()
    {
        $this->memberRoleId = App::MEMBER_ROLE_ID_MEMBER;

        return $this;
    }

    /**
     * @return $this
     */
    public function makeModerator()
    {
        $this->memberRoleId = App::MEMBER_ROLE_ID_MODERATOR;
        $this->alertAction = 'promoted_moderator';

        return $this;
    }

    /**
     * @return $this
     */
    public function removeModerator()
    {
        $this->memberRoleId = App::MEMBER_ROLE_ID_MEMBER;

        return $this;
    }

    /**
     * @return void
     */
    public function promote()
    {
        /** @var MemberRole|null $memberRole */
        $memberRole = $this->em()->find('Truonglv\Groups:MemberRole', $this->memberRoleId);
        if ($memberRole === null) {
            throw new LogicException('Nothing to promote.');
        }

        $member = $this->member;
        $member->member_role_id = $memberRole->member_role_id;

        $saved = null;
        $member->saveIfChanged($saved);

        if ($saved) {
            if ($member->isReceiveAlertType(App::MEMBER_ALERT_OPT_ALERT_ONLY)) {
                $this->sendNotifications();
            }

            if ($this->logAction && $member->Group !== null) {
                App::logAction($member->Group, 'member', $member->user_id, 'promote', [
                    'new_member_role_id' => $this->memberRoleId,
                    'old_member_role_id' => $member->getExistingValue('member_role_id')
                ]);
            }
        }
    }

    /**
     * @return void
     */
    protected function sendNotifications()
    {
        $member = $this->member;
        $visitor = XF::visitor();

        if ($this->alertAction !== null && $member->User !== null) {
            App::alert(
                $member->User,
                $visitor->user_id,
                $visitor->username,
                $member->getEntityContentType(),
                $member->member_id,
                $this->alertAction
            );
        }
    }
}
