<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF;
use Truonglv\Groups\App;
use XF\Job\AbstractRebuildJob;

class MemberRebuild extends AbstractRebuildJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'remove_deleted' => false,
        'remove_banned' => false
    ];

    /**
     * @param mixed $start
     * @param mixed $batch
     * @return array
     */
    protected function getNextIds($start, $batch)
    {
        $db = $this->app->db();

        return $db->fetchAllColumn($db->limit('
            SELECT user_id
            FROM xf_tl_group_member
            WHERE user_id > ?
            GROUP BY user_id
            ORDER BY user_id
        ', $batch), $start);
    }

    /**
     * @return \XF\Phrase
     */
    protected function getStatusType()
    {
        return XF::phrase('tlg_members');
    }

    /**
     * @param mixed $id
     * @throws \XF\PrintableException
     * @return void
     */
    protected function rebuildById($id)
    {
        /** @var \XF\Entity\User|null $user */
        $user = XF::em()->find('XF:User', $id);
        $cleanUser = false;

        if ($user === null) {
            $cleanUser = (bool) $this->data['remove_deleted'];
        } elseif ($user->is_banned) {
            $cleanUser = (bool) $this->data['remove_banned'];
        }

        if ($cleanUser) {
            $members = App::memberFinder()
                ->where('user_id', $id)
                ->fetch();
            /** @var \Truonglv\Groups\Entity\Member $member */
            foreach ($members as $member) {
                $member->delete(false);
            }
        } elseif ($user !== null) {
            $this->app->db()->update('xf_tl_group_member', ['username' => $user->username], 'user_id = ?', $id);
        }
    }
}
