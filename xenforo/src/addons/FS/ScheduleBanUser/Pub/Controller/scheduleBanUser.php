<?php

namespace FS\ScheduleBanUser\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class scheduleBanUser extends AbstractController
{
    public function actionAdd(ParameterBag $params)
    {
        if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {

            return $this->message('Not Allowed');
        }

        $userBan = $this->Finder('FS\ScheduleBanUser:ScheduleBanUser')->where('user_id', $params->user_id)->fetchOne();

        if (!$userBan) {
            $userBan = $this->em()->create('FS\ScheduleBanUser:ScheduleBanUser');
        }

        $viewpParams = [
            'userBanned' => $userBan,
            'user_id' => $params['user_id']
        ];

        return $this->view('FS\ScheduleBanUser', 'fs_user_ban_addEdit', $viewpParams);
    }


    public function actionSave()
    {

        $input = $this->filter(['user_id' => 'int']);

        $userBan = $this->Finder('FS\ScheduleBanUser:ScheduleBanUser')->where('user_id', $input['user_id'])->fetchOne();

        if (!$userBan) {
            $userBan = $this->em()->create('FS\ScheduleBanUser:ScheduleBanUser');
        }

        $input = $this->filter([
            'ban_date' => 'str',
            'user_id' => 'int',
            'ban_time' => 'str',
            'ban_reason' => 'str',
        ]);


        $ban_Date = $this->getDateTimeCurrent($input['ban_date'], $input['ban_time']);

        if ($ban_Date < time()) {
            return $this->error(\XF::phrase('fs_schedule_Please_enter_future_date'));
        }
        $userBan->bulkSet([
            'user_banBy_id' => \XF::visitor()->user_id,
            'user_id' => $input['user_id'],
            'ban_date' => $ban_Date,
            'ban_reason' => $input['ban_reason'],
        ]);

        $userBan->save();

        return $this->redirect($this->buildLink('members/' . $input['user_id']));
    }

    public function getDateTimeCurrent($date, $time)
    {
        $timezone = \xf::options()->fs_scheduled_ban_user_timezone;

        $tz = new \DateTimeZone($timezone);

        $dateTime = new \DateTime("@" . strtotime($date), $tz);

        list($hours, $minutes) = explode(':', $time);


        $dateTime->setTime($hours, $minutes);
        return $dateTime->getTimestamp();
    }
    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \CRUD\XF\Entity\Crud
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\ScheduleBanUser:ScheduleBanUser', $id, $extraWith, $phraseKey);
    }

    public function actionDelete(ParameterBag $params)
    {
        $user = $this->Finder('FS\ScheduleBanUser:ScheduleBanUser')->where('user_id', $params->user_id)->fetchOne();
        if ($user) {
            /**  @var \FS\ScheduleBanUser\Entity\ScheduleBanUser $replyExists */
            $replyExists = $this->assertDataExists($user->ban_id);

            /** @var \XF\ControllerPlugin\Delete $plugin */
            $plugin = $this->plugin('XF:Delete');

            if ($this->isPost()) {

                $this->preDeletethread($replyExists);

                return $this->redirect($this->buildLink('members/' . $replyExists->user_id));
            }

            return $plugin->actionDelete(
                $replyExists,
                $this->buildLink('scheduleBanUser/delete', $replyExists),
                null,
                $this->buildLink('scheduleBanUser'),
                \XF::phrase('fs_are_you_sure_to_cancel_user_ban') . $replyExists->User->username
            );
        } else {
            return $this->redirect($this->buildLink('members/' . $params['user_id']), 'User for ban Added Successfully.', 'permanent');
        }
    }
    public function preDeletethread($user_ban)
    {
        $user_ban->delete();
    }

    public function permissionCheck()
    {
        if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {
            return $this->message('Not Allowed');
        }
    }
}
