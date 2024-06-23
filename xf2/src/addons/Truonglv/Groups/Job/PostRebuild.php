<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF;
use XF\Job\AbstractRebuildJob;
use Truonglv\Groups\Entity\Post;

class PostRebuild extends AbstractRebuildJob
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
            SELECT `post_id`
            FROM `xf_tl_group_post`
            WHERE `post_id` > ?
            ORDER BY `post_id`
        ', $batch), $start);
    }

    /**
     * @return \XF\Phrase
     */
    protected function getStatusType()
    {
        return XF::phrase('tlg_posts');
    }

    /**
     * @param mixed $id
     * @throws \XF\PrintableException
     * @return void
     */
    protected function rebuildById($id)
    {
        /** @var Post|null $post */
        $post = $this->app->em()->find('Truonglv\Groups:Post', $id);
        if ($post === null) {
            return;
        }

        $post->rebuildFirstComment();
        if ($post->first_comment_id <= 0) {
            $post->delete();
        } else {
            $post->rebuildCounters();
            $post->saveIfChanged();
        }
    }
}
