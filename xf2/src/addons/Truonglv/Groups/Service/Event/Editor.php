<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Event;

use Exception;
use LogicException;
use function array_merge;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Event;
use Truonglv\Groups\Entity\Comment;
use XF\Service\ValidateAndSavableTrait;

class Editor extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var Preparer
     */
    protected $eventPreparer;

    /**
     * @var \Truonglv\Groups\Service\Comment\Preparer
     */
    protected $commentPreparer;

    /**
     * @var \Truonglv\Groups\Entity\Comment
     */
    protected $comment;

    public function __construct(\XF\App $app, Event $event)
    {
        parent::__construct($app);

        if (!$event->exists()) {
            throw new LogicException('Event must be exists.');
        }

        $this->setEvent($event);
    }

    /**
     * @return Event
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
     * @param Event $event
     * @return void
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
        /** @var Comment $firstComment */
        $firstComment = $event->FirstComment;
        $this->comment = $firstComment;

        $this->event->addCascadedSave($this->comment);

        /** @var Preparer $eventPreparer */
        $eventPreparer = $this->service('Truonglv\Groups:Event\Preparer', $event);
        $this->eventPreparer = $eventPreparer;

        /** @var \Truonglv\Groups\Service\Comment\Preparer $commentPreparer */
        $commentPreparer = $this->service('Truonglv\Groups:Comment\Preparer', $this->comment);
        $this->commentPreparer = $commentPreparer;
    }

    /**
     * @param string $message
     * @param bool $format
     * @return void
     */
    public function setMessage(string $message, $format = true)
    {
        $this->commentPreparer->setMessage($message, $format);
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
     * @param string $attachmentHash
     * @return void
     */
    public function setAttachmentHash(string $attachmentHash)
    {
        $this->commentPreparer->setAttachmentHash($attachmentHash);
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
        $this->eventPreparer->setPerformValidations(false);
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->event->preSave();

        $errors = $this->event->getErrors();
        $errors = array_merge($errors, $this->eventPreparer->validate());

        return $errors;
    }

    /**
     * @return Event
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $db->beginTransaction();

        $event = $this->event;

        $event->save(true, false);

        $this->eventPreparer->afterUpdate();
        $this->commentPreparer->afterUpdate();

        $db->commit();

        return $event;
    }
}
