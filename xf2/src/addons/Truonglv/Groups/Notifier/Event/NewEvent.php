<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Notifier\Event;

use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Event;
use XF\Notifier\AbstractNotifier;

class NewEvent extends AbstractNotifier
{
    /**
     * @var Event
     */
    protected $event;
    /**
     * @var string
     */
    protected $actionType;

    /**
     * NewEvent constructor.
     * @param \XF\App $app
     * @param Event $event
     * @param string $actionType
     */
    public function __construct(\XF\App $app, Event $event, $actionType)
    {
        parent::__construct($app);

        $this->event = $event;
        $this->actionType = $actionType;
    }

    /**
     * @return array
     */
    public function getDefaultNotifyData()
    {
        $memberNotifier = $this->app->notifier('Truonglv\Groups:Member', $this->event->Group);

        return $memberNotifier->getDefaultNotifyData();
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     */
    public function canNotify(\XF\Entity\User $user)
    {
        return $user->user_id !== $this->event->user_id;
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     */
    public function sendAlert(\XF\Entity\User $user)
    {
        return App::alert(
            $user,
            $this->event->user_id,
            $this->event->username,
            $this->event->getEntityContentType(),
            $this->event->event_id,
            $this->actionType
        );
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     */
    public function sendEmail(\XF\Entity\User $user)
    {
        if ($user->email === '' || $user->user_state !== 'valid') {
            return false;
        }

        $event = $this->event;

        $params = [
            'event' => $event,
            'group' => $event->Group,
            'receiver' => $user
        ];

        $this->app()->mailer()->newMail()
            ->setToUser($user)
            ->setTemplate('tlg_group_new_event', $params)
            ->queue();

        return true;
    }
}
