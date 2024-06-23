<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Post;

use XF;
use LogicException;
use XF\Entity\User;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Post;
use Truonglv\Groups\Entity\Group;
use XF\Service\ValidateAndSavableTrait;
use Truonglv\Groups\Service\Comment\Preparer;
use Truonglv\Groups\Service\CommentableTrait;

class Creator extends AbstractService
{
    use CommentableTrait, ValidateAndSavableTrait;

    /**
     * @var Group
     */
    protected $group;
    /**
     * @var Post
     */
    protected $post;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Preparer
     */
    protected $commentPreparer;

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        $this->group = $group;
        $this->setupDefaults();
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return void
     */
    public function sendNotifications()
    {
        /** @var \Truonglv\Groups\Service\Comment\Notifier $notifier */
        $notifier = $this->service('Truonglv\Groups:Comment\Notifier', $this->comment, 'post');
        $notifier->setMentionedUserIds($this->commentPreparer->getMentionedUserIds());
        $notifier->setQuotedUserIds($this->commentPreparer->getQuotedUserIds());
        $notifier->notifyAndEnqueue(3);
    }

    public function setIsAutomated(): void
    {
        $this->commentPreparer->logIp(false);
    }

    /**
     * @return void
     */
    protected function finalizeSetup()
    {
        $post = $this->post;
        $postDate = time();

        $post->group_id = $this->group->group_id;
        $post->user_id = $this->user->user_id;
        $post->username = $this->user->username;
        $post->post_date = $postDate;

        $post->last_comment_date = $postDate;

        $this->setupComment($this->user, $post->getCommentContentType(), 0, $postDate);
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->finalizeSetup();

        $post = $this->post;
        $post->preSave();

        $errors = $post->getErrors();

        return $errors;
    }

    /**
     * @return Post
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $post = $this->post;
        $comment = $this->getComment();

        $db->beginTransaction();

        $post->save(true, false);
        $comment->fastUpdate('content_id', $post->post_id);

        $post->fastUpdate([
            'first_comment_id' => $comment->comment_id
        ]);

        $this->commentPreparer->afterInsert();

        $db->commit();

        return $post;
    }

    /**
     * @param User $user
     * @return void
     */
    protected function setUser(User $user)
    {
        if ($user->user_id <= 0) {
            throw new LogicException('User must be saved!');
        }

        $this->user = $user;
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        $this->setUser(XF::visitor());

        /** @var Post $post */
        $post = $this->em()->create('Truonglv\Groups:Post');
        $this->post = $post;

        $this->setupCommentDefaults($comment);

        $comment->content_type = 'post';
        $post->addCascadedSave($comment);
        $post->hydrateRelation('FirstComment', $comment);
    }
}
