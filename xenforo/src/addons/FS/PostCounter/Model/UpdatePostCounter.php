<?php

/** 
 * @author Thomas Braunberger
 */

namespace FS\PostCounter\Model;

class UpdatePostCounter
{
    public static function updatePostCounter()
    {
        $db = \XF::db();
        $pcTableName = self::getPostCounterTableName();

        $class = \XF::extendClass('FS\PostCounter\Model\PostAndThreadStats');

        /* @var $stats \FS\PostCounter\Model\PostAndThreadStats */
        $stats = new $class;

        // delete old data                
        $db->query("DELETE FROM $pcTableName");

        // get highest user_id
        $lastUserId = $db->fetchOne('SELECT COUNT(*) FROM xf_user');

        // update post and thread counts
        for ($userId = 1; $userId <= $lastUserId; $userId++) {
            $allStats = $stats->getPostAndThreadCounts($userId);

            foreach ($allStats as $subForumStats) {
                $nodeId = $subForumStats['node_id'];
                $postCount = $subForumStats['post_count'];
                $threadCount = $subForumStats['thread_count'];

                $db->query("INSERT INTO $pcTableName VALUES ($userId, $nodeId, $postCount, $threadCount)");
            }
        }
    }

    public static function getPostCounterTableName()
    {
        return \XF::em()->getEntityStructure('FS\PostCounter:PostCounter')->table;
    }
}
