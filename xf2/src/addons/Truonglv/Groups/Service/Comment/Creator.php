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
use function get_class;
use XF\Mvc\Entity\Entity;
use XF\Service\AbstractService;
use XF\Service\ValidateAndSavableTrait;
use Truonglv\Groups\Service\CommentableTrait;

class Creator extends AbstractService
{
    use ValidateAndSavableTrait, CommentableTrait;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Entity
     */
    protected $container;

    /**
     * @var Preparer
     */
    protected $commentPreparer;

    public function __construct(\XF\App $app, Entity $container)
    {
        parent::__construct($app);

        $this->container = $container;

        $this->setupDefaults();
    }

    /**
     * @return void
     */
    public function sendNotifications()
    {
        /** @var \Truonglv\Groups\Service\Comment\Notifier $notifier */
        $notifier = $this->service('Truonglv\Groups:Comment\Notifier', $this->comment, 'comment');
        $notifier->setMentionedUserIds($this->commentPreparer->getMentionedUserIds());
        $notifier->setQuotedUserIds($this->commentPreparer->getQuotedUserIds());
        $notifier->notifyAndEnqueue(3);
    }

    public function setIsAutomated(): void
    {
        $this->commentPreparer->logIp(false);
    }

    /**
     * @param User $user
     * @return void
     */
    protected function setUser(User $user)
    {
        if (!$user->exists()) {
            throw new LogicException('User must be exists.');
        }

        $this->user = $user;
    }

    /**
     * @return void
     */
    protected function finalizeSetup()
    {
        if (!method_exists($this->container, 'getCommentContentType')) {
            throw new LogicException(
                'Entity ('
                . get_class($this->container) . ') must implement method `getCommentContentType`'
            );
        }

        $this->setupComment(
            $this->user,
            $this->container->getCommentContentType(),
            (int) $this->container->getEntityId(),
            time()
        );
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
     * @return \Truonglv\Groups\Entity\Comment
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $comment = $this->comment;

        $db->beginTransaction();

        $comment->save(true, false);
        $this->commentPreparer->afterInsert();

        $db->commit();

        return $comment;
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        $this->setUser(XF::visitor());
        $this->setupCommentDefaults($comment);

        $this->comment->setContent($this->container);
    }
}
