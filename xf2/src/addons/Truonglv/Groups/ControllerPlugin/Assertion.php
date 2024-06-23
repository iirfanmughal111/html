<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\ControllerPlugin;

use XF;
use function is_array;
use XF\Mvc\ParameterBag;
use InvalidArgumentException;
use XF\ControllerPlugin\AbstractPlugin;

class Assertion extends AbstractPlugin
{
    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param int $categoryId
     * @return \Truonglv\Groups\Entity\Category
     */
    public function assertCategoryViewable($categoryId)
    {
        /** @var \Truonglv\Groups\Entity\Category|null $category */
        $category = $this->em()->find('Truonglv\Groups:Category', $categoryId);
        if ($category === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception(
                $this->controller->notFound(XF::phrase('tlg_requested_category_not_found'))
            );
        }

        if (!$category->canView($error)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception(
                $this->controller->noPermission($error)
            );
        }

        return $category;
    }

    /**
     * @param int|ParameterBag $data
     * @param array $extraWith
     * @param bool $viewContent
     * @return \Truonglv\Groups\Entity\Group
     */
    public function assertGroupViewable($data, array $extraWith = [], $viewContent = false)
    {
        $extraWith[] = 'full';

        if ($data instanceof ParameterBag) {
            $groupId = $data->group_id;
        } else {
            $groupId = $data;
        }

        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = $this->em()->find('Truonglv\Groups:Group', $groupId, $extraWith);
        if ($group === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception(
                $this->controller->notFound(XF::phrase('tlg_requested_group_not_found'))
            );
        }
        
        if ($viewContent) {
            $allowed = $group->canViewContent($error);
        } else {
            $allowed = $group->canView($error);
        }

        if (!$allowed) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception($this->controller->noPermission($error));
        }

        return $group;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param int $forumId
     * @param array $extraWith
     * @return \XF\Entity\Forum
     */
    public function assertForumViewable($forumId, array $extraWith = [])
    {
        /** @var \XF\Entity\Forum|null $forum */
        $forum = $this->em()->find('XF:Forum', $forumId, $extraWith);
        if ($forum === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception(
                $this->controller->notFound(XF::phrase('requested_forum_not_found'))
            );
        }

        return $forum;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param string|\XF\Mvc\ParameterBag|array $data
     * @param array $extraWith
     * @return \Truonglv\Groups\Entity\Member
     */
    public function assertMemberViewable($data, array $extraWith = [])
    {
        $extraWith[] = 'User';

        $memberId = null;
        $userId = null;
        $groupId = null;

        if ($data instanceof ParameterBag) {
            if ($data->offsetExists('member_id')) {
                $memberId = $data->offsetGet('member_id');
            } elseif ($data->offsetExists('user_id')
                && $data->offsetExists('group_id')
            ) {
                $userId = $data->offsetGet('user_id');
                $groupId = $data->offsetGet('group_id');
            }
        } elseif (is_array($data)) {
            if (isset($data['member_id'])) {
                $memberId = $data['member_id'];
            } elseif (isset($data['user_id']) && isset($data['group_id'])) {
                $userId = $data['user_id'];
                $groupId = $data['group_id'];
            }
        } else {
            throw new InvalidArgumentException('Unknown $data type!');
        }

        /** @var \Truonglv\Groups\Entity\Member|null $member */
        $member = null;

        if ($memberId > 0) {
            /** @var \Truonglv\Groups\Entity\Member|null $member */
            $member = $this->em()->find('Truonglv\Groups:Member', $memberId, $extraWith);
        } else {
            /** @var \Truonglv\Groups\Entity\Member|null $member */
            $member = $this->em()->findOne('Truonglv\Groups:Member', [
                'user_id' => $userId,
                'group_id' => $groupId
            ], $extraWith);
        }

        if ($member === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception(
                $this->controller->notFound(XF::phrase('requested_member_not_found'))
            );
        }

        if (!$member->canView($error)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception($this->controller->noPermission($error));
        }

        return $member;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param int|ParameterBag $data
     * @param array|string $extraWith
     * @return \Truonglv\Groups\Entity\Event
     */
    public function assertEventViewable($data, $extraWith = [])
    {
        if (!is_array($extraWith)) {
            $extraWith = [$extraWith];
        }

        if ($data instanceof ParameterBag) {
            $eventId = $data->event_id;
        } else {
            $eventId = $data;
        }

        /** @var \Truonglv\Groups\Entity\Event|null $event */
        $event = $this->em()->find('Truonglv\Groups:Event', $eventId, $extraWith);
        if ($event === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception(
                $this->controller->notFound(XF::phrase('tlg_requested_event_not_found'))
            );
        }

        if (!$event->canView($errors)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception($this->controller->noPermission($errors));
        }

        return $event;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param int|ParameterBag $data
     * @param array $extraWith
     * @return \Truonglv\Groups\Entity\Comment
     */
    public function assertCommentViewable($data, array $extraWith = [])
    {
        $extraWith[] = 'full';

        if ($data instanceof ParameterBag) {
            $commentId = $data->comment_id;
        } else {
            $commentId = $data;
        }

        /** @var \Truonglv\Groups\Entity\Comment|null $comment */
        $comment = $this->em()->find('Truonglv\Groups:Comment', $commentId, $extraWith);
        if ($comment === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception(
                $this->controller->notFound(XF::phrase('tlg_requested_comment_not_found'))
            );
        }

        if (!$comment->canView($error)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $this->controller->exception($this->controller->noPermission($error));
        }

        return $comment;
    }
}
