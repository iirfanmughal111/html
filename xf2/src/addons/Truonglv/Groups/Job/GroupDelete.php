<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF\Timer;
use Exception;
use XF\Job\AbstractJob;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Finder;

class GroupDelete extends AbstractJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'group_id' => null,

        'totalEvents' => null,
        'totalMembers' => null,
        'totalAlbums' => null,
        'totalPosts' => null,

        'deletedEvents' => 0,
        'deletedMembers' => 0,
        'deletedAlbums' => 0,
        'deletedPosts' => 0,
    ];

    /**
     * @return bool
     */
    public function canTriggerByChoice()
    {
        return false;
    }

    /**
     * @param mixed $maxRunTime
     * @return \XF\Job\JobResult
     */
    public function run($maxRunTime)
    {
        if ($this->data['group_id'] <= 0) {
            return $this->complete();
        }

        $db = $this->app->db();
        $groupId = $this->data['group_id'];

        if ($this->data['totalEvents'] === null) {
            $this->data['totalEvents'] = $db->fetchOne('
                SELECT COUNT(*)
                FROM xf_tl_group_event
                WHERE group_id = ?
            ', $groupId);
        }
        if ($this->data['totalMembers'] === null) {
            $this->data['totalMembers'] = $db->fetchOne('
                SELECT COUNT(*)
                FROM xf_tl_group_member
                WHERE group_id = ?
            ', $groupId);
        }
        if ($this->data['totalAlbums'] === null) {
            try {
                $this->data['totalAlbums'] = $db->fetchOne('
                    SELECT COUNT(*)
                    FROM xf_mg_album
                    WHERE tlg_group_id = ?
                ', $groupId);
            } catch (\XF\Db\Exception $e) {
            }
        }
        if ($this->data['totalPosts'] === null) {
            $this->data['totalPosts'] = $db->fetchOne('
                SELECT COUNT(*)
                FROM xf_tl_group_post
                WHERE group_id = ?
            ', $groupId);
        }

        $timer = new Timer($maxRunTime);
        $continue = false;

        $eventFinder = App::eventFinder()->where('group_id', $groupId);
        $memberFinder = App::memberFinder()->where('group_id', $groupId);
        $postFinder = App::postFinder()->where('group_id', $groupId);

        try {
            $albumFinder = $this->app->finder('XFMG:Album')->where('tlg_group_id', $groupId);
        } catch (Exception $e) {
            $albumFinder = null;
        }

        $enableMedia = (int) App::getOption('enableMedia');
        if ($this->deleteContents($eventFinder, $timer, 'deletedEvents', 'totalEvents')) {
            $continue = true;
        } elseif ($this->deleteContents($memberFinder, $timer, 'deletedMembers', 'totalMembers')) {
            $continue = true;
        } elseif ($albumFinder !== null
            && $enableMedia === 1
            && $this->deleteContents($albumFinder, $timer, 'deletedAlbums', 'totalAlbums')
        ) {
            $continue = true;
        } elseif ($this->deleteContents($postFinder, $timer, 'deletedPosts', 'totalPosts')) {
            $continue = true;
        }

        return $continue ? $this->resume() : $this->complete();
    }

    /**
     * @param Finder $finder
     * @param Timer $timer
     * @param string $countKey
     * @param string $totalKey
     * @return bool
     */
    protected function deleteContents(Finder $finder, Timer $timer, $countKey, $totalKey)
    {
        if (!$this->hasRemainData($countKey, $totalKey)) {
            return false;
        }

        foreach ($finder->fetch(50) as $entity) {
            if ($timer->limitExceeded()) {
                break;
            }

            $entity->delete(false);

            $this->data[$countKey]++;
        }

        return $this->hasRemainData($countKey, $totalKey);
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return '';
    }

    /**
     * @param string $countKey
     * @param string $totalKey
     * @return bool
     */
    protected function hasRemainData($countKey, $totalKey)
    {
        return $this->data[$countKey] < $this->data[$totalKey];
    }
}
