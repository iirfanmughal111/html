<?php

namespace Truonglv\Groups\Service\Member;

use XF;
use Truonglv\Groups\App;
use XF\PrintableException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Member;

class Banning extends AbstractService
{
    /**
     * @var Member
     */
    protected $member;

    /**
     * @var bool
     */
    protected $logAction = true;

    public function __construct(\XF\App $app, Member $member)
    {
        parent::__construct($app);

        $this->member = $member;
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
     * @param int $endDate
     * @throws PrintableException
     * @return void
     */
    public function ban($endDate = 0)
    {
        if ($endDate > 0 && $endDate <= XF::$time) {
            throw new PrintableException(XF::phrase('please_enter_a_date_in_the_future'));
        }

        $member = $this->member;

        $member->member_state = App::MEMBER_STATE_BANNED;
        $member->ban_end_date = $endDate;

        $member->save();

        if ($this->logAction && $member->Group !== null) {
            App::logAction(
                $member->Group,
                'member',
                $member->user_id,
                'ban',
                [
                    'end_date' => $endDate
                ]
            );
        }
    }

    /**
     * @throws PrintableException
     * @return void
     */
    public function liftBan()
    {
        $member = $this->member;

        $member->member_state = App::MEMBER_STATE_VALID;
        $member->ban_end_date = 0;

        $member->save();
        if ($this->logAction && $member->Group !== null) {
            App::logAction(
                $member->Group,
                'member',
                $member->user_id,
                'lift_ban'
            );
        }
    }
}
