<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Event;

use DateTime;
use Exception;
use Throwable;
use DateTimeZone;
use function trim;
use Truonglv\Groups\App;
use function array_merge;
use XF\Service\Tag\Changer;
use InvalidArgumentException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Event;
use Truonglv\Groups\Entity\Group;

class Preparer extends AbstractService
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var \XF\Service\Tag\Changer
     */
    protected $tagChanger;

    /**
     * @var \Truonglv\Groups\Entity\Group
     */
    protected $group;

    /**
     * @var bool
     */
    protected $performValidations = true;

    public function __construct(\XF\App $app, Event $event, Group $group = null)
    {
        parent::__construct($app);

        $this->event = $event;
        if ($group !== null) {
            $this->group = $group;
        } elseif ($event->Group !== null) {
            $this->group = $event->Group;
        } else {
            throw new InvalidArgumentException('Cannot determine group');
        }

        /** @var Changer $tagChanger */
        $tagChanger = $this->service(
            'XF:Tag\Changer',
            App::CONTENT_TYPE_EVENT,
            $this->event->exists() ? $this->event : $group
        );

        $this->tagChanger = $tagChanger;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string|array $tags
     * @return void
     */
    public function setTags($tags)
    {
        if ($this->tagChanger->canEdit()) {
            $this->tagChanger->setEditableTags($tags);
        }
    }

    /**
     * @param bool $performValidations
     */
    public function setPerformValidations(bool $performValidations): void
    {
        $this->performValidations = $performValidations;
    }

    public function setEventDateRaw(string $column, array $date): void
    {
        $date = array_replace([
            'date' => '',
            'hour' => '', // H:i
        ], $date);

        $dateString = sprintf(
            '%s %s:00',
            $date['date'],
            $date['hour']
        );

        $this->setEventDate($column, $dateString);
    }

    /**
     * @param string $column
     * @param string $dateString
     * @return void
     * @throws Exception
     */
    public function setEventDate(string $column, string $dateString)
    {
        $dateString = trim($dateString);
        if ($dateString === '') {
            $this->event->set($column, 0);

            return;
        }

        $timeZone = $this->event->getTimeZone();

        try {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $dateString, new DateTimeZone($timeZone));
            $this->event->set($column, $dt->getTimestamp());
        } catch (Throwable $e) {
        }
    }

    /**
     * @return array
     */
    public function validate()
    {
        $errors = [];

        if ($this->tagChanger->canEdit() && $this->performValidations) {
            if ($this->tagChanger->hasErrors()) {
                $errors = array_merge($errors, $this->tagChanger->getErrors());
            }
        }

        return $errors;
    }

    /**
     * @return void
     */
    public function afterInsert()
    {
        if ($this->tagChanger->canEdit() && $this->tagChanger->tagsChanged()) {
            $this->tagChanger
                ->setContentId($this->event->event_id, true)
                ->save();
        }
    }

    /**
     * @return void
     */
    public function afterUpdate()
    {
        if ($this->tagChanger->canEdit() && $this->tagChanger->tagsChanged()) {
            $this->tagChanger->save();
        }
    }
}
