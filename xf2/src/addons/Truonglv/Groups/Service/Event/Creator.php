<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Event;

use XF;
use Exception;
use function time;
use LogicException;
use XF\Entity\User;
use function array_merge;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\EventGuest;
use XF\Service\ValidateAndSavableTrait;
use Truonglv\Groups\Service\CommentableTrait;

class Creator extends AbstractService
{
    use ValidateAndSavableTrait, CommentableTrait;
    /**
     * @var Group
     */
    protected $group;

    /**
     * @var \Truonglv\Groups\Entity\Event
     */
    protected $event;

    /**
     * @var \Truonglv\Groups\Service\Event\Preparer
     */
    protected $eventPreparer;

    /**
     * @var \XF\Entity\User
     */
    protected $user;

    /**
     * @var EventGuest
     */
    protected $guest;

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        $this->group = $group;
        $this->setupDefaults();
    }

    /**
     * @return \Truonglv\Groups\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return Preparer
     */
    public function getEventPreparer(): Preparer
    {
        return $this->eventPreparer;
    }

    /**
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        if (!$user->exists()) {
            throw new LogicException('User must be exists.');
        }

        $this->user = $user;
        $this->event->user_id = $user->user_id;
        $this->event->username = $user->username;
        $this->event->hydrateRelation('User', $user);
    }

    /**
     * @param string|array $tags
     * @return void
     */
    public function setTags($tags)
    {
        $this->eventPreparer->setTags($tags);
    }

    /**
     * @param string $date
     * @throws Exception
     * @return void
     */
    public function setBeginDate(string $date)
    {
        $this->eventPreparer->setEventDate('begin_date', $date);
    }

    /**
     * @param string $date
     * @throws Exception
     * @return void
     */
    public function setEndDate(string $date)
    {
        $this->eventPreparer->setEventDate('end_date', $date);
    }

    public function setIsAutomated(): void
    {
        $this->commentPreparer->logIp(false);
        $this->eventPreparer->setPerformValidations(false);
    }

    /**
     * @return void
     */
    protected function finalizeSetup()
    {
        $eventPublishedDate = time();

        $event = $this->event;
        $event->created_date = $eventPublishedDate;

        $event->last_comment_date = $eventPublishedDate;

        $this->guest->user_id = $this->user->user_id;
        $this->guest->intend = 'going';

        $this->setupComment($this->user, $event->getCommentContentType(), 0, $eventPublishedDate);
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->finalizeSetup();

        $this->event->preSave();
        $errors = $this->event->getErrors();

        $errors = array_merge($errors, $this->eventPreparer->validate());

        return $errors;
    }

    /**
     * @return \Truonglv\Groups\Entity\Event
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $db->beginTransaction();

        $event = $this->event;
        $comment = $this->comment;

        $event->save(true, false);
        $comment->fastUpdate('content_id', $event->event_id);

        $event->fastUpdate([
            'first_comment_id' => $comment->comment_id
        ]);

        $this->eventPreparer->afterInsert();
        $this->commentPreparer->afterInsert();

        $db->commit();

        return $event;
    }

    /**
     * @return void
     */
    public function sendNotifications()
    {
        /** @var \Truonglv\Groups\Service\Comment\Notifier $notifier */
        $notifier = $this->service('Truonglv\Groups:Event\Notifier', $this->event, Notifier::ACTION_CREATED);
        $notifier->notifyAndEnqueue(3);
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        $event = $this->group->getNewEvent();
        $this->event = $event;

        $this->setupCommentDefaults();

        $this->comment->setGroup($this->group);
        $event->addCascadedSave($this->comment);
        $event->hydrateRelation('FirstComment', $this->comment);

        $this->setUser(XF::visitor());

        /** @var Preparer $eventPreparer */
        $eventPreparer = $this->service('Truonglv\Groups:Event\Preparer', $event, $this->group);
        $this->eventPreparer = $eventPreparer;

        $guest = $event->getNewGuest();
        $guest->setOption(EventGuest::OPTION_KEY_AUTO_WATCH, false);
        $event->addCascadedSave($guest);
        $this->guest = $guest;
    }
}
