<?php

namespace Truonglv\Groups\Job;

use XF;
use DateTime;
use function count;
use XF\Job\JobResult;
use XF\Job\AbstractJob;
use Truonglv\Groups\App;
use function array_column;
use Truonglv\Groups\Entity\Event;
use Truonglv\Groups\Service\Event\Notifier;

class EventReminder extends AbstractJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'eventIds' => null,
    ];

    /**
     * @param mixed $maxRunTime
     * @return JobResult
     */
    public function run($maxRunTime)
    {
        $reminderCutoff = (int) XF::app()->options()->tl_groups_eventReminder;
        if ($reminderCutoff === 0) {
            return $this->complete();
        }

        if ($this->data['eventIds'] === null) {
            $dt = new DateTime();
            $dt->setTime($reminderCutoff, 0, 0);

            $startDate = (int) $dt->format('U');
            $endDate = $startDate + 3600 - 1;

            $eventFinder = App::eventFinder();

            $eventFinder->where('begin_date', 'BETWEEN', [$startDate, $endDate]);
            $eventFinder->order('begin_date');

            $results = $eventFinder->fetchColumns('event_id');
            $eventIds = array_column($results, 'event_id');

            $this->data['eventIds'] = $eventIds;
        }

        $eventIds = $this->data['eventIds'];
        if (count($eventIds) === 0) {
            return $this->complete();
        }

        foreach ($eventIds as $index => $eventId) {
            unset($eventIds[$index]);

            /** @var Event|null $event */
            $event = $this->app->em()->find('Truonglv\Groups:Event', $eventId, 'full');
            if ($event === null) {
                continue;
            }

            /** @var Notifier $notifier */
            $notifier = $this->app->service('Truonglv\Groups:Event\Notifier', $event, Notifier::ACTION_REMINDER);
            $notifier->notifyAndEnqueue(3);
        }

        $this->data['eventIds'] = $eventIds;

        return count($eventIds) > 0 ? $this->resume() : $this->complete();
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return '';
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canTriggerByChoice()
    {
        return false;
    }
}
