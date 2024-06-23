<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use function count;
use function gettype;
use function is_array;
use function array_keys;
use Truonglv\Groups\App;
use function array_values;
use InvalidArgumentException;
use XF\Mvc\Entity\Repository;
use XF\Mvc\Entity\AbstractCollection;

class Comment extends Repository
{
    /**
     * @param string $contentType
     * @param int $contentId
     * @param int $parentId
     * @return \Truonglv\Groups\Finder\Comment
     */
    public function findCommentsForView($contentType, $contentId, $parentId = 0)
    {
        /** @var \Truonglv\Groups\Finder\Comment $finder */
        $finder = App::commentFinder();
        $finder
            ->inContent($contentType, $contentId)
            ->where('parent_id', $parentId);
        $finder->indexHint('FORCE', 'content_type_id_parent');

        return $finder;
    }

    /**
     * @param \Truonglv\Groups\Entity\Event $event
     * @return \Truonglv\Groups\Finder\Comment
     */
    public function findCommentsForEventView(\Truonglv\Groups\Entity\Event $event)
    {
        $finder = $this->findCommentsForView(
            $event->getCommentContentType(),
            $event->event_id
        );
        $finder->with('full');

        return $finder;
    }

    /**
     * @param \Truonglv\Groups\Entity\Event $event
     * @param int $newerThan
     * @return \Truonglv\Groups\Finder\Comment
     */
    public function findNewestCommentsInEvent(\Truonglv\Groups\Entity\Event $event, $newerThan)
    {
        $finder = $this->findCommentsForView($event->getCommentContentType(), $event->event_id);
        $finder->resetOrder();
        $finder->with('full');

        $finder
            ->orderByDate('DESC')
            ->newerThan($newerThan);

        return $finder;
    }

    /**
     * @param mixed $comments
     * @return mixed
     */
    public function addRecentRepliesIntoComments($comments)
    {
        $recentReplyIds = [];
        /** @var \Truonglv\Groups\Entity\Comment $comment */
        foreach ($comments as $comment) {
            /** @var mixed $mixed */
            $mixed = $comment->latest_reply_ids;
            if (!is_array($mixed)) {
                continue;
            }

            foreach ($comment->latest_reply_ids as $id) {
                $recentReplyIds[$id] = $comment->comment_id;
            }
        }

        if (count($recentReplyIds) === 0) {
            return $comments;
        }

        $replies = $this->em->findByIds('Truonglv\Groups:Comment', array_keys($recentReplyIds), 'full');
        /** @var \Truonglv\Groups\Entity\Comment $comment */
        foreach ($comments as $comment) {
            $commentReplies = $this->em->getEmptyCollection();
            /** @var mixed $mixed */
            $mixed = $comment->latest_reply_ids;
            if (!is_array($mixed)) {
                continue;
            }

            foreach ($comment->latest_reply_ids as $id) {
                if (isset($replies[$id])) {
                    /** @var \Truonglv\Groups\Entity\Comment $commentEntity */
                    $commentEntity = $replies[$id];
                    $commentEntity->setContent($comment->Content);

                    $commentReplies[$id] = $commentEntity;
                }
            }

            $comment->setLatestReplies($commentReplies);
        }

        return $comments;
    }

    /**
     * @param mixed $comments
     * @return mixed
     */
    public function addContentIntoComments($comments)
    {
        if ($comments instanceof \Truonglv\Groups\Entity\Comment) {
            return $this->addContentIntoComments($this->em->getBasicCollection([
                $comments->comment_id => $comments
            ]));
        }

        if (!($comments instanceof AbstractCollection)) {
            throw new InvalidArgumentException(
                'Expect AbstractCollection argument but got: ' . gettype($comments)
            );
        }

        if ($comments->count() === 0) {
            return $comments;
        }

        $postIdMap = [];
        $eventIdMap = [];
        $resourceIdMap = [];

        /** @var \Truonglv\Groups\Entity\Comment $comment */
        foreach ($comments as $comment) {
            if ($comment->content_type === 'post') {
                $postIdMap[$comment->comment_id] = $comment->content_id;
            } elseif ($comment->content_type === 'event') {
                $eventIdMap[$comment->comment_id] = $comment->content_id;
            } elseif ($comment->content_type === 'resource') {
                $resourceIdMap[$comment->comment_id] = $comment->content_id;
            }
        }

        if (count($postIdMap) > 0) {
            $posts = $this->em->findByIds('Truonglv\Groups:Post', array_values($postIdMap), 'full');

            $this->setContentIntoCommentsFromMap($postIdMap, $posts, $comments);
        } elseif (count($eventIdMap) > 0) {
            $events = $this->em->findByIds('Truonglv\Groups:Event', array_values($eventIdMap), 'full');
            $this->setContentIntoCommentsFromMap($eventIdMap, $events, $comments);
        } elseif (count($resourceIdMap) > 0) {
            $resources = $this->em->findByIds(
                'Truonglv\Groups:ResourceItem',
                array_values($resourceIdMap),
                'fullView'
            );
            $this->setContentIntoCommentsFromMap($resourceIdMap, $resources, $comments);
        }

        return $comments;
    }

    /**
     * @param array $map
     * @param AbstractCollection $contents
     * @param AbstractCollection $comments
     * @return void
     */
    protected function setContentIntoCommentsFromMap(array $map, AbstractCollection $contents, AbstractCollection $comments)
    {
        foreach ($map as $commentId => $contentId) {
            $contentRef = isset($contents[$contentId]) ? $contents[$contentId] : null;
            /** @var \Truonglv\Groups\Entity\Comment|null $commentRef */
            $commentRef = isset($comments[$commentId]) ? $comments[$commentId] : null;

            if ($commentRef !== null) {
                $commentRef->setContent($contentRef);
            }
        }
    }
}
