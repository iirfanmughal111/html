<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Notifier\Event;

use LogicException;
use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Event;
use XF\Notifier\AbstractNotifier;
use Truonglv\Groups\Entity\Comment;

class EventWatch extends AbstractNotifier
{
    /**
     * @var Comment
     */
    protected $comment;
    /**
     * @var string
     */
    protected $actionType;

    public function __construct(\XF\App $app, Comment $comment, string $actionType)
    {
        parent::__construct($app);

        switch ($actionType) {
            case 'comment':
                break;
            default:
                throw new LogicException('Unknown action type (' . $actionType . ')');
        }

        $this->comment = $comment;
        $this->actionType = $actionType;
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     */
    public function canNotify(\XF\Entity\User $user)
    {
        return $user->user_id !== $this->comment->user_id
            && !$user->is_banned
            && $user->user_state === 'valid';
    }

    /**
     * @return array
     */
    public function getDefaultNotifyData()
    {
        $comment = $this->comment;
        /** @var Event $event */
        $event = $comment->Content;

        return App::eventRepo()->getEventWatchNotifyData($event);
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     */
    public function sendAlert(\XF\Entity\User $user)
    {
        return App::alert(
            $user,
            $this->comment->user_id,
            $this->comment->username,
            App::CONTENT_TYPE_COMMENT,
            $this->comment->comment_id,
            'event_' . $this->actionType
        );
    }
}
