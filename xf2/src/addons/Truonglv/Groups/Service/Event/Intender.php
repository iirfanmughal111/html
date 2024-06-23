<?php

namespace Truonglv\Groups\Service\Event;

use XF;
use XF\Entity\User;
use Truonglv\Groups\App;
use InvalidArgumentException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Event;
use Truonglv\Groups\Entity\EventGuest;
use XF\Service\ValidateAndSavableTrait;

class Intender extends AbstractService
{
    use ValidateAndSavableTrait;
    /**
     * @var Event
     */
    protected $event;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var EventGuest
     */
    protected $guest;

    public function __construct(\XF\App $app, Event $event)
    {
        parent::__construct($app);

        $this->event = $event;
        $this->setUser(XF::visitor());

        $this->setupDefaults();
    }

    /**
     * @param string $intend
     * @return void
     */
    public function setIntend(string $intend)
    {
        $this->guest->intend = $intend;
    }

    /**
     * @return void
     */
    public function sendNotifications()
    {
        if ($this->user->user_id === $this->event->user_id
            || $this->guest->intend === 'not_going'
        ) {
            return;
        }

        if ($this->event->User !== null) {
            App::alert(
                $this->event->User,
                $this->user->user_id,
                $this->user->username,
                App::CONTENT_TYPE_EVENT,
                $this->event->event_id,
                'intend_' . $this->guest->intend
            );
        }
    }

    /**
     * @param User $user
     * @return void
     */
    protected function setUser(User $user)
    {
        if ($user->user_id <= 0) {
            throw new InvalidArgumentException('User must saved!');
        }

        $this->user = $user;
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->guest->preSave();
        $errors = $this->guest->getErrors();

        if ($this->event->max_attendees > 0
            && $this->guest->isChanged('intend')
            && $this->guest->intend ===  EventGuest::INTEND_GOING
            && count($errors) === 0
        ) {
            $count = $this->finder('Truonglv\Groups:EventGuest')
                ->where('event_id', $this->event->event_id)
                ->where('intend', EventGuest::INTEND_GOING)
                ->where('user_id', '<>', $this->event->user_id)
                ->total();
            if ($count >= $this->event->max_attendees) {
                $errors[] = XF::phrase('tlg_event_reached_maximum_attendees_its_allowed');
            }
        }

        return $errors;
    }

    /**
     * @return EventGuest
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->app->db();
        $db->beginTransaction();

        $this->guest->save(true, false);

        $db->commit();

        return $this->guest;
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        /** @var EventGuest|null $guest */
        $guest = $this->finder('Truonglv\Groups:EventGuest')
            ->where('event_id', $this->event->event_id)
            ->where('user_id', $this->user->user_id)
            ->fetchOne();
        if ($guest === null) {
            /** @var EventGuest $guest */
            $guest = $this->em()->create('Truonglv\Groups:EventGuest');
            $guest->event_id = $this->event->event_id;
            $guest->user_id = $this->user->user_id;
        }

        $this->guest = $guest;
    }
}
