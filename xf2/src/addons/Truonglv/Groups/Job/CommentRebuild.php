<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF;
use XF\Job\AbstractRebuildJob;
use Truonglv\Groups\Entity\Comment;

class CommentRebuild extends AbstractRebuildJob
{
    /**
     * @param mixed $start
     * @param mixed $batch
     * @return array
     */
    protected function getNextIds($start, $batch)
    {
        $db = $this->app->db();

        return $db->fetchAllColumn($db->limit('
            SELECT `comment_id`
            FROM `xf_tl_group_comment`
            WHERE `comment_id` = ?
            ORDER BY `comment_id`
        ', $batch), $start);
    }

    /**
     * @return \XF\Phrase
     */
    protected function getStatusType()
    {
        return XF::phrase('tlg_comments');
    }

    /**
     * @param mixed $id
     * @return void
     */
    protected function rebuildById($id)
    {
        /** @var Comment|null $comment */
        $comment = $this->app->em()->find('Truonglv\Groups:Comment', $id);
        if ($comment === null) {
            return;
        }

        $comment->rebuildLatestReplies();
        $comment->saveIfChanged();
    }
}
