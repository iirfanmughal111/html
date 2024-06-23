<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Notifier\Post;

use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Post;
use XF\Notifier\AbstractNotifier;

class NewPosts extends AbstractNotifier
{
    /**
     * @var Post
     */
    protected $post;

    public function __construct(\XF\App $app, Post $post)
    {
        parent::__construct($app);

        $this->post = $post;
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     */
    public function canNotify(\XF\Entity\User $user)
    {
        return $user->user_id !== $this->post->user_id;
    }

    /**
     * @return array
     */
    public function getDefaultNotifyData()
    {
        $notifyData = [];

        $results = App::memberFinder()
            ->inGroup($this->post->Group)
            ->with('User')
            ->alertable()
            ->where('User.user_state', '=', 'valid')
            ->where('User.is_banned', '=', 0)
            ->fetchColumns(['alert', 'user_id']);
        foreach ($results as $result) {
            $notifyData[$result['user_id']] = [
                'email' => App::memberRepo()->isEnableAlertFor($result['alert'], App::MEMBER_ALERT_OPT_EMAIL_ONLY),
                'alert' => App::memberRepo()->isEnableAlertFor($result['alert'], App::MEMBER_ALERT_OPT_ALERT_ONLY)
            ];
        }

        return $notifyData;
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     */
    public function sendAlert(\XF\Entity\User $user)
    {
        return App::alert(
            $user,
            $this->post->user_id,
            $this->post->username,
            App::CONTENT_TYPE_COMMENT,
            $this->post->first_comment_id,
            'new_post'
        );
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     */
    public function sendEmail(\XF\Entity\User $user)
    {
        if ($user->email === '' || $user->user_state !== 'valid') {
            return false;
        }

        $params = [
            'post' => $this->post,
            'group' => $this->post->Group,
            'receiver' => $user,
        ];

        $this->app()->mailer()->newMail()
            ->setToUser($user)
            ->setTemplate('tlg_group_new_post', $params)
            ->queue();

        return true;
    }
}
