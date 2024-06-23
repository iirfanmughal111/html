<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Comment;

use XF;
use function time;
use LogicException;
use XF\Entity\User;
use InvalidArgumentException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Comment;
use XF\Service\ValidateAndSavableTrait;
use Truonglv\Groups\Service\CommentableTrait;

class Replier extends AbstractService
{
    use CommentableTrait, ValidateAndSavableTrait;
    /**
     * @var Comment
     */
    protected $parentComment;
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Preparer
     */
    protected $commentPreparer;

    public function __construct(\XF\App $app, Comment $parentComment)
    {
        parent::__construct($app);

        $this->parentComment = $parentComment;
        $this->setupDefaults();
    }

    /**
     * @return void
     */
    public function sendNotifications()
    {
        /** @var \Truonglv\Groups\Service\Comment\Notifier $notifier */
        $notifier = $this->service('Truonglv\Groups:Comment\Notifier', $this->comment, 'reply');
        $notifier->setMentionedUserIds($this->commentPreparer->getMentionedUserIds());
        $notifier->setQuotedUserIds($this->commentPreparer->getQuotedUserIds());
        $notifier->notifyAndEnqueue(3);
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        $this->setUser(XF::visitor());
        $this->setupCommentDefaults();

        $comment = $this->comment;
        $parentComment = $this->parentComment;

        $this->setupComment($this->user, $parentComment->content_type, $parentComment->content_id, time());

        if ($parentComment->parent_id > 0) {
            throw new InvalidArgumentException('Too many comment level');
        }

        $comment->parent_id = $parentComment->comment_id;
        $comment->hydrateRelation('ParentComment', $parentComment);
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
    protected function finalizeSetup()
    {
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->finalizeSetup();

        $comment = $this->comment;
        $comment->preSave();

        return $comment->getErrors();
    }

    /**
     * @return Comment
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $db->beginTransaction();

        $comment = $this->comment;

        $comment->save(true, false);
        $this->commentPreparer->afterInsert();

        $db->commit();

        return $comment;
    }
}
