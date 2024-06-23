<?php

namespace Truonglv\Groups\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $event_id
 * @property int $user_id
 * @property string $intend
 *
 * RELATIONS
 * @property \Truonglv\Groups\Entity\Event $Event
 * @property \XF\Entity\User $User
 */
class EventGuest extends Entity
{
    const INTEND_GOING = 'going';
    const INTEND_MAYBE = 'maybe';
    const INTEND_NOT_GOING = 'not_going';

    const OPTION_KEY_AUTO_WATCH = 'optionAutoWatch';

    public function isHost(): bool
    {
        if ($this->Event === null) {
            return false;
        }

        return $this->Event->user_id === $this->user_id;
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_event_guest';
        $structure->primaryKey = ['event_id', 'user_id'];
        $structure->shortName = 'Truonglv\Groups:EventGuest';

        $structure->columns = [
            'event_id' => ['type' => self::UINT, 'required' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'intend' => [
                'type' => self::STR,
                'allowedValues' => [
                    self::INTEND_GOING,
                    self::INTEND_MAYBE,
                    self::INTEND_NOT_GOING
                ],
                'default' => self::INTEND_MAYBE
            ]
        ];

        $structure->relations = [
            'Event' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Event',
                'conditions' => 'event_id',
                'primary' => true
            ],
            'User' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:User',
                'conditions' => 'user_id',
                'primary' => true
            ]
        ];

        $structure->options = [
            self::OPTION_KEY_AUTO_WATCH => true
        ];

        return $structure;
    }

    protected function _postSave()
    {
        $autoWatch = (bool) $this->getOption(self::OPTION_KEY_AUTO_WATCH);
        if ($this->isInsert() && $autoWatch) {
            $this->autoWatchEventForIntender();
        }

        if ($this->isChanged('intend')
            && $this->Event !== null
            && !$this->isHost()
        ) {
            $intendChanges = $this->isStateChanged('intend', self::INTEND_GOING);
            if ($intendChanges === 'enter') {
                $this->Event->attendee_count++;
                $this->Event->save();
            } elseif ($intendChanges === 'leave') {
                $this->Event->attendee_count--;
                $this->Event->saveIfChanged();
            }
        }
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    protected function autoWatchEventForIntender()
    {
        if ($this->intend === 'not_going') {
            return;
        }

        /** @var EventWatch|null $watch */
        $watch = $this->finder('Truonglv\Groups:EventWatch')
            ->where('event_id', $this->event_id)
            ->where('user_id', $this->user_id)
            ->fetchOne();

        if ($watch === null) {
            /** @var EventWatch $watch */
            $watch = $this->em()->create('Truonglv\Groups:EventWatch');
            $watch->event_id = $this->event_id;
            $watch->user_id = $this->user_id;
            $watch->save();
        }
    }
}
