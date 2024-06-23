<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Notifier\Comment;

use Truonglv\Groups\App;
use XF\Notifier\AbstractNotifier;
use Truonglv\Groups\Entity\Comment;

class Mention extends AbstractNotifier
{
    /**
     * @var Comment
     */
    protected $comment;

    public function __construct(\XF\App $app, Comment $comment)
    {
        parent::__construct($app);

        $this->comment = $comment;
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
            'mention'
        );
    }
}
