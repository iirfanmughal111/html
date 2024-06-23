<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Util;

use Truonglv\Groups\Entity\Event;

class EventObject implements \JsonSerializable
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var bool
     */
    public $allDay = false;

    /**
     * @var string
     */
    public $start;

    /**
     * @var string
     */
    public $end;

    /**
     * @var string
     */
    public $url;

    /**
     * @var array
     */
    public $className = [];

    /**
     * @var string
     */
    public $color = '';

    /**
     * @var string
     */
    public $backgroundColor = '';

    /**
     * @var string
     */
    public $borderColor = '';

    /**
     * @var string
     */
    public $textColor = '';

    /**
     * @var string
     */
    public $previewUrl = '';

    /**
     * @param Event $event
     * @return EventObject
     */
    public static function fromEvent(Event $event)
    {
        $object = new EventObject();

        $object->id = $event->event_id;
        $object->title = $event->event_name;

        $object->start = DateParser::toISO8601($event->begin_date);
        $object->end = DateParser::toISO8601($event->end_date);

        $object->previewUrl = \XF::app()->router('public')->buildLink('canonical:group-events/preview', $event);
        $object->url = \XF::app()->router('public')->buildLink('canonical:group-events', $event);

        return $object;
    }

    /**
     * @param array $events
     * @return array
     */
    public static function fromEvents($events)
    {
        $results = [];

        foreach ($events as $event) {
            $results[] = static::fromEvent($event);
        }

        return $results;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
