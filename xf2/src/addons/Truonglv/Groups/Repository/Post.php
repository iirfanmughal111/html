<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use XF;
use function count;
use function array_keys;
use Truonglv\Groups\App;
use function json_encode;
use XF\Mvc\Entity\Repository;
use XF\Mvc\Entity\AbstractCollection;

class Post extends Repository
{
    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param string $with
     * @return \XF\Mvc\Entity\Finder
     */
    public function findPostsInGroup(\Truonglv\Groups\Entity\Group $group, $with = 'full')
    {
        $postFinder = App::postFinder();

        $postFinder->with($with);
        $postFinder->where('group_id', $group->group_id);
        $postFinder->setDefaultOrder('last_comment_date', 'DESC');

        return $postFinder;
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param string $with
     * @return \XF\Mvc\Entity\Finder
     */
    public function findStickyPostsInGroup(\Truonglv\Groups\Entity\Group $group, $with = 'full')
    {
        $finder = $this->findPostsInGroup($group, $with);
        $finder->where('sticky', 1);

        return $finder;
    }

    /**
     * @param AbstractCollection $posts
     * @param bool $loadFirstComment
     * @param bool $loadRecentReplies
     * @return AbstractCollection
     */
    public function addLatestCommentsIntoPosts($posts, $loadFirstComment = true, $loadRecentReplies = true)
    {
        $commentIdsMap = [];
        /** @var \Truonglv\Groups\Entity\Post $post */
        foreach ($posts as $post) {
            foreach ($post->latest_comment_ids as $id) {
                $commentIdsMap[$id] = $post->post_id;
            }
            if ($loadFirstComment) {
                $commentIdsMap[$post->first_comment_id] = $post->post_id;
            }
        }

        $comments = [];
        if (count($commentIdsMap) > 0) {
            $comments = $this->em
                ->findByIds('Truonglv\Groups:Comment', array_keys($commentIdsMap), 'full');

            App::attachmentRepo()->addAttachmentsToContent($comments, App::CONTENT_TYPE_COMMENT);
            if ($loadRecentReplies) {
                $comments = App::commentRepo()->addRecentRepliesIntoComments($comments);
            }
        }

        $invalidPosts = [];

        /** @var \Truonglv\Groups\Entity\Post $post */
        foreach ($posts as $index => $post) {
            if ($loadFirstComment) {
                /** @var \Truonglv\Groups\Entity\Comment|null $firstComment */
                $firstComment = $comments[$post->first_comment_id];
                if ($firstComment === null) {
                    $invalidPosts[] = $post->post_id;
                    unset($posts[$index]);

                    continue;
                }

                $firstComment->setContent($post);
                $post->hydrateRelation('FirstComment', $firstComment);
            }

            $postComments = [];
            foreach ($post->latest_comment_ids as $id) {
                if (isset($comments[$id])) {
                    /** @var \Truonglv\Groups\Entity\Comment $commentEntity */
                    $commentEntity = $comments[$id];
                    $commentEntity->setContent($post);

                    $postComments[$id] = $commentEntity;
                }
            }

            $post->setLatestComments($this->em->getBasicCollection($postComments));
        }

        if (count($invalidPosts) > 0) {
            XF::logError(sprintf(
                '[tl] Social Groups: Posts without first comment. $ids=%s',
                json_encode($invalidPosts)
            ));
        }

        return $posts;
    }
}
