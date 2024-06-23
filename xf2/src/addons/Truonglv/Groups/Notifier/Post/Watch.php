<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Notifier\Post;

use Truonglv\Groups\App;
use XF\Notifier\AbstractNotifier;
use Truonglv\Groups\Entity\Comment;

class Watch extends AbstractNotifier
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
        $db = $this->app->db();

        $notifyData = [];
        $comment = $this->comment;

        $results = $db->fetchAll('
            SELECT `member`.`alert`, `member`.`user_id`
            FROM `xf_tl_group_comment` AS `comment`
                INNER JOIN `xf_tl_group_post` AS `post`
                    ON (`post`.`post_id` = `comment`.`content_id` AND `comment`.`content_type` = \'post\')
                LEFT JOIN `xf_tl_group_member` AS `member`
                    ON (`member`.`user_id` = `comment`.`user_id` AND `member`.`group_id` = `post`.`group_id`)
                LEFT JOIN `xf_user` AS `user`
                    ON (`user`.`user_id` = `member`.`user_id`)
            WHERE `comment`.`content_id` = ?
                AND `comment`.`content_type` = ?
                AND `member`.`alert` <> ?
                AND `member`.`member_state` = ?
        ', [
            $comment->content_id,
            $comment->content_type,
            App::MEMBER_ALERT_OPT_OFF,
            App::MEMBER_STATE_VALID
        ]);

        foreach ($results as $result) {
            $notifyData[$result['user_id']] = [
                'alert' => App::memberRepo()->isEnableAlertFor($result['alert'], App::MEMBER_ALERT_OPT_ALERT_ONLY),
                'email' => App::memberRepo()->isEnableAlertFor($result['alert'], App::MEMBER_ALERT_OPT_EMAIL_ONLY)
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
        $comment = $this->comment;

        return App::alert(
            $user,
            $comment->user_id,
            $comment->username,
            App::CONTENT_TYPE_COMMENT,
            $comment->comment_id,
            $this->actionType === 'reply' ? 'reply' : ('post_' . $this->actionType)
        );
    }
}
