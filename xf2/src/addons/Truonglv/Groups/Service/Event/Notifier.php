<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Event;

use XF;
use Exception;
use function in_array;
use InvalidArgumentException;
use XF\Service\AbstractNotifier;
use Truonglv\Groups\Entity\Event;

class Notifier extends AbstractNotifier
{
    const ACTION_CREATED = 'created';
    const ACTION_REMINDER = 'reminder';

    /**
     * @var Event
     */
    protected $event;
    /**
     * @var string
     */
    protected $action;

    public function __construct(\XF\App $app, Event $event, string $action)
    {
        parent::__construct($app);

        if (!in_array($action, [self::ACTION_CREATED, self::ACTION_REMINDER], true)) {
            throw new InvalidArgumentException("Action must be 'created' or 'reminder'");
        }

        $this->event = $event;
        $this->action = $action;
    }

    /**
     * @param array $extraData
     * @return \XF\Service\AbstractService|null
     */
    public static function createForJob(array $extraData)
    {
        /** @var Event|null $event */
        $event = XF::em()->find('Truonglv\Groups:Event', $extraData['eventId']);
        if ($event === null) {
            return null;
        }

        return XF::service('Truonglv\Groups:Event\Notifier', $event, $extraData['action']);
    }

    /**
     * @return array
     */
    protected function getExtraJobData()
    {
        return [
            'eventId' => $this->event->event_id,
            'action' => $this->action
        ];
    }

    /**
     * @return array
     */
    protected function loadNotifiers()
    {
        $notifiers = [];

        if ($this->action === self::ACTION_CREATED
            || $this->action === self::ACTION_REMINDER
        ) {
            $notifiers['newEvent'] = $this->app->notifier(
                'Truonglv\Groups:Event\NewEvent',
                $this->event,
                $this->action
            );
        }

        return $notifiers;
    }

    /**
     * @param array $users
     * @return void
     */
    protected function loadExtraUserData(array $users)
    {
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     * @throws Exception
     */
    protected function canUserViewContent(\XF\Entity\User $user)
    {
        return XF::asVisitor($user, function () {
            return $this->event->canView();
        });
    }
}
