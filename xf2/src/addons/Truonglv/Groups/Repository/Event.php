<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use XF;
use DateTime;
use DateTimeZone;
use XF\Entity\User;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Repository;
use XF\Mvc\Entity\AbstractCollection;

class Event extends Repository
{
    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param array $limits
     * @return \Truonglv\Groups\Finder\Event
     */
    public function findEventsForView(\Truonglv\Groups\Entity\Group $group, array $limits = [])
    {
        $finder = App::eventFinder();
        $finder->inGroup($group)
               ->with('full')
               ->setDefaultOrder('last_comment_date', 'DESC');

        return $finder;
    }

    /**
     * @return array
     * @deprecated
     */
    public static function getIntendOptions()
    {
        return ['going', 'maybe', 'not_going'];
    }

    /**
     * @return array
     */
    public function getIntendLabelPairs()
    {
        $pairs = [];

        /** @var \Truonglv\Groups\Entity\EventGuest $eventGuest */
        $eventGuest = $this->em->create('Truonglv\Groups:EventGuest');
        foreach ($eventGuest->structure()->columns['intend']['allowedValues'] as $intend) {
            // @phpstan-ignore-next-line
            $pairs[$intend] = XF::phraseDeferred('tlg_event_intend_' . $intend);
        }
        unset($eventGuest);

        return $pairs;
    }

    /**
     * @param \Truonglv\Groups\Entity\Event $event
     * @return array
     */
    public function getEventWatchNotifyData(\Truonglv\Groups\Entity\Event $event)
    {
        $db = $this->db();

        $activeLimit = App::getOption('watchAlertActiveOnly');
        $whereLimit = '';

        if ($activeLimit['enabled'] > 0) {
            $limit = XF::$time - $activeLimit['days'] * 86400;
            $whereLimit = 'AND `view`.`view_date` >= ' . $db->quote($limit);
        }

        $notifyData = [];
        $results = $db->fetchAll('
            SELECT `member`.`alert`, `member`.`user_id`
            FROM `xf_tl_group_event_watch` AS `event_watch`
                INNER JOIN `xf_tl_group_event` AS `event` ON (`event`.`event_id` = `event_watch`.`event_id`)
                LEFT JOIN `xf_tl_group_member` AS `member` ON
                    (`member`.`user_id` = `event_watch`.`user_id` AND `member`.`group_id` = `event`.`group_id`)
                LEFT JOIN `xf_tl_group_view` AS `view` ON
                    (`view`.`group_id` = `member`.`group_id` AND `view`.`user_id` = `member`.`user_id`)
            WHERE `event_watch`.`event_id` = ?
                AND `member`.`alert` <> ?
                AND `member`.`member_state` = ?
                ' . $whereLimit . '
        ', [
            $event->event_id,
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
     * @param \Truonglv\Groups\Entity\Event $event
     * @param User|null $user
     * @return void
     */
    public function watchEvent(\Truonglv\Groups\Entity\Event $event, User $user = null)
    {
        $user = $user !== null ? $user : XF::visitor();

        try {
            $this->db()->insert('xf_tl_group_event_watch', [
                'event_id' => $event->event_id,
                'user_id' => $user->user_id
            ]);
        } catch (\XF\Db\Exception $e) {
        }
    }

    public function getEventsDataForCalendar(AbstractCollection $events, ?DateTimeZone $timeZone = null): array
    {
        $data = [];

        if ($timeZone === null) {
            $timeZone = new DateTimeZone(XF::visitor()->timezone);
        }

        $router = $this->app()->router('public');

        /** @var \Truonglv\Groups\Entity\Event $event */
        foreach ($events as $event) {
            $data[] = array_replace([
                'id' => 'event-' . $event->event_id,
                'groupId' => $event->group_id,
                'allDay' => false,
                'start' => (new DateTime('@' . $event->begin_date))->setTimezone($timeZone)->format(DateTime::ISO8601),
                'end' => $event->end_date > 0
                    ? (new DateTime('@' . $event->end_date))->setTimezone($timeZone)->format(DateTime::ISO8601)
                    : null,
                'title' => $event->event_name,
                'url' => $router->buildLink('canonical:group-events', $event),
                'classNames' => ['event-item'],
                'editable' => $event->canEdit(),
                'editLink' => $router->buildLink('group-events/quick-edit', $event),
            ]);
        }

        return $data;
    }
}
