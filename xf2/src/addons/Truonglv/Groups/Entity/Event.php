<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use DateTime;
use DateTimeZone;
use function count;
use function round;
use XF\Entity\User;
use Truonglv\Groups\App;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;
use function array_column;
use XF\Mvc\Entity\Structure;
use function http_build_query;

/**
 * COLUMNS
 * @property int|null $event_id
 * @property string $event_name
 * @property int $created_date
 * @property int $begin_date
 * @property int $end_date
 * @property string $timezone
 * @property int $cover_date
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property array $tags
 * @property string $location_type
 * @property string $virtual_address
 * @property int $cancelled_date
 * @property int $max_attendees
 * @property int $attendee_count
 * @property int $group_id
 * @property int $user_id
 * @property string $username
 * @property int $first_comment_id
 * @property array $latest_comment_ids
 * @property int $last_comment_date
 * @property int $comment_count
 *
 * GETTERS
 * @property string $display_address
 * @property ArrayCollection|null $LatestComments
 *
 * RELATIONS
 * @property \XF\Mvc\Entity\AbstractCollection|\Truonglv\Groups\Entity\EventWatch[] $Watched
 * @property \XF\Mvc\Entity\AbstractCollection|\Truonglv\Groups\Entity\EventGuest[] $Guests
 * @property \XF\Entity\User $User
 * @property \Truonglv\Groups\Entity\Group $Group
 * @property \Truonglv\Groups\Entity\Comment $FirstComment
 */
class Event extends Entity
{
    const LOCATION_TYPE_VIRTUAL = 'virtual';
    const LOCATION_TYPE_REAL = 'real';
    const LOCATION_TYPE_NONE = '';

    use CommentableTrait;

    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        /** @var Group|null $group */
        $group = $this->Group;
        if ($group === null) {
            return false;
        }

        if (!$group->canViewEvents($error)) {
            return false;
        }

        return $group->canViewContent($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canEdit(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }
        $visitor = XF::visitor();

        $member = $this->getMember();
        if ($member === null) {
            return false;
        }

        if ($member->hasRole(App::MEMBER_ROLE_PERM_KEY_EVENT, 'editAny')) {
            return true;
        }

        return ($visitor->user_id == $this->user_id
            && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_EVENT, 'editOwn'));
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canEditTags(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        return $this->canEdit($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canDelete(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        $visitor = XF::visitor();

        $member = $this->getMember();
        if ($member === null) {
            return false;
        }

        if ($member->hasRole(App::MEMBER_ROLE_PERM_KEY_EVENT, 'deleteAny')) {
            return true;
        }

        return ($visitor->user_id == $this->user_id
            && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_EVENT, 'deleteOwn'));
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canCancel(& $error = null)
    {
        return $this->canEdit($error) || $this->canDelete($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canWatchUnwatch(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if ($this->getMember() === null) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canIntend(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if ($this->isOpening() || $this->end_date <= XF::$time) {
            return false;
        }

        $member = $this->getMember();
        if ($member === null || !$member->isValidMember()) {
            return false;
        }

        /** @var EventGuest|null $guest */
        $guest = $this->Guests[$visitor->user_id];
        if ($guest !== null) {
            // allow user can their mind later
            return true;
        }

        if ($this->max_attendees > 0 && $this->attendee_count >= $this->max_attendees) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isIntended()
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        return isset($this->Guests[$visitor->user_id]);
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canComment(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        $group = $this->Group;
        if ($group === null) {
            return false;
        }

        if ($group->isPublicGroup()) {
            return true;
        }

        $member = $this->getMember();
        if ($member === null) {
            return false;
        }

        return $member->hasRole(App::MEMBER_ROLE_PERM_KEY_EVENT, 'comment');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUploadAndManageAttachments(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        /** @var Group|null $group */
        $group = $this->Group;
        if ($group === null) {
            return false;
        }

        return $group->canUploadAndManageAttachments($error);
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        /** @var Group|null $group */
        $group = $this->Group;
        if ($group === null) {
            return false;
        }

        return $group->isVisible();
    }

    /**
     * @return bool
     */
    public function isIgnored()
    {
        return XF::visitor()->isIgnoring($this->user_id);
    }

    /**
     * @return bool
     */
    public function isWatched()
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        return isset($this->Watched[$visitor->user_id]);
    }

    /**
     * Event is opening
     *
     * @return bool
     */
    public function isOpening()
    {
        if ($this->isCancelled()) {
            return false;
        }

        return ($this->begin_date <= XF::$time && $this->end_date >= XF::$time);
    }

    public function isCancelled(): bool
    {
        return $this->cancelled_date > 0;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUseInlineModeration(& $error = null)
    {
        if (!App::hasPermission('inlineMod')) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canViewAttachments(& $error = null)
    {
        // Depends to view event
        return $this->canView($error);
    }

    /**
     * @return string|null
     */
    public function getEmbedMapUrl()
    {
        if ($this->address === ''
            || $this->location_type !== self::LOCATION_TYPE_REAL
        ) {
            return null;
        }

        if ($this->latitude != 0 && $this->longitude != 0) {
            $query = $this->latitude . ',' . $this->longitude;
        } else {
            $query = $this->address;
        }

        $language = $this->app()->language(XF::visitor()->language_id);

        return 'https://maps.google.com/maps?' . http_build_query([
            'q' => $query,
            'hl' => $language->getLanguageCode(),
            'z' => 17,
            'output' => 'embed'
        ], '', '&');
    }

    /**
     * @param string|null $context
     * @return mixed
     */
    public function getBeginDateOutput(?string $context = null)
    {
        if ($context === 'edit') {
            $dt = new DateTime('@' . $this->begin_date);
            $dt->setTimezone(new DateTimeZone($this->getTimeZone()));
            $minute = ceil($dt->format('i') / 15) * 15;
            if ($minute >= 60) {
                $minute = 0;
            }

            return [
                'date' => $dt->format('Y-m-d'),
                'hour' => sprintf('%02d:%02d', $dt->format('H'), $minute),
            ];
        }

        $visitor = XF::visitor();

        return $this->app()->language($visitor->language_id)->dateTime($this->begin_date);
    }

    /**
     * @param string|null $context
     * @return mixed
     */
    public function getEndDateOutput(?string $context = null)
    {
        if ($context === 'edit') {
            if ($this->end_date <= 0) {
                return [];
            }

            $dt = new DateTime('@' . $this->end_date);
            $dt->setTimezone(new DateTimeZone($this->getTimeZone()));
            $minute = ceil($dt->format('i') / 15) * 15;
            if ($minute >= 60) {
                $minute = 0;
            }

            return [
                'date' => $dt->format('Y-m-d'),
                'hour' => sprintf('%02d:%02d', $dt->format('H'), $minute),
            ];
        }

        if ($this->end_date <= 0) {
            return null;
        }

        $visitor = XF::visitor();

        return $this->app()->language($visitor->language_id)->dateTime($this->end_date);
    }

    /**
     * @return Member|null
     */
    public function getMember()
    {
        /** @var Group|null $group */
        $group = $this->Group;
        if ($group === null) {
            return null;
        }

        return $group->Member;
    }

    /**
     * @return \Truonglv\Groups\Entity\Comment
     */
    public function setupFirstComment()
    {
        /** @var \Truonglv\Groups\Entity\Comment $entity */
        $entity = $this->em()->create('Truonglv\Groups:Comment');
        $entity->content_type = 'event';
        $entity->content_id = $this->_getDeferredValue(function () {
            return $this->event_id;
        }, 'save');

        return $entity;
    }

    /**
     * @return EventGuest
     */
    public function getNewGuest()
    {
        /** @var EventGuest $guest */
        $guest = $this->em()->create('Truonglv\Groups:EventGuest');
        $guest->event_id = $this->_getDeferredValue(function () {
            return $this->event_id;
        }, 'save');

        return $guest;
    }

    /**
     * @return string
     */
    public function getCommentContentType()
    {
        return 'event';
    }

    public function getTimeZone(): string
    {
        if ($this->timezone === '_option') {
            $timeZone = App::getOption('eventTimeZone');
            $defaultTimeZone = $this->app()->options()->guestTimeZone;
            if ($timeZone === 'visitor') {
                /** @var User|null $user */
                $user = $this->User;

                return $user->timezone ?? $defaultTimeZone;
            } elseif ($timeZone === 'system') {
                return $defaultTimeZone;
            }

            return $timeZone;
        }

        return $this->timezone;
    }

    /**
     * @return array|null
     */
    public function getSchemaStructuredData()
    {
        if ($this->FirstComment === null
            || $this->User === null
            || $this->Group === null
        ) {
            return null;
        }

        $timeZone = $this->getTimeZone();

        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone($timeZone));

        $output = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $this->event_name,
            'url' => $this->app()->router('public')->buildLink('canonical:group-events', $this),
            'description' => $this->app()->stringFormatter()->stripBbCode($this->FirstComment->message),
            'alternateName' => $this->event_name,
            'commentCount' => $this->comment_count,
            'dateCreated' => $dt->setTimestamp($this->created_date)->format('c'),
            'datePublished' => $dt->setTimestamp($this->begin_date)->format('c'),
            'expires' => $dt->setTimestamp($this->end_date)->format('c'),
            'discussionUrl' => $this->app()->router('public')->buildLink('canonical:group-events', $this),
        ];

        if ($this->FirstComment->attach_count > 0) {
            $images = [];
            /** @var Attachment $attachment */
            foreach ($this->FirstComment->Attachments as $attachment) {
                if ($attachment->has_thumbnail) {
                    $images[] = $attachment->thumbnail_url_full;
                }
            }

            if (count($images) > 0) {
                $output['image'] = $images;
            }
        } elseif ($userAvatarUrl = $this->User->getAvatarUrl('l', null, true)) {
            $output['image'] = [$userAvatarUrl];
        } elseif (($groupAvatarUrl = $this->Group->getAvatarUrl(true)) !== null) {
            $output['image'] = [$groupAvatarUrl];
        }

        if (count($this->tags) > 0) {
            $output['keywords'] = array_column($this->tags, 'tag');
        }

        return $output;
    }

    /**
     * @return string
     */
    public function getDisplayAddress()
    {
        if ($this->location_type === self::LOCATION_TYPE_NONE) {
            return '';
        }

        if ($this->location_type === self::LOCATION_TYPE_VIRTUAL) {
            return $this->virtual_address;
        }

        return $this->address;
    }

    public function getRemainingAttendeesSlots(): int
    {
        return max(0, $this->max_attendees - $this->attendee_count);
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_event';
        $structure->primaryKey = 'event_id';
        $structure->contentType = App::CONTENT_TYPE_EVENT;
        $structure->shortName = 'Truonglv\Groups:Event';

        $structure->columns = [
            'event_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'event_name' => [
                'type' => self::STR,
                'maxLength' => 150,
                // \XF::phrase('tlg_please_enter_valid_event_name')
                'required' => 'tlg_please_enter_valid_event_name'
            ],

            'created_date' => ['type' => self::UINT, 'default' => time()],

            'begin_date' => [
                'type' => self::UINT,
                // \XF::phrase('tlg_please_enter_valid_begin_date')
                'required' => 'tlg_please_enter_valid_begin_date'
            ],
            'end_date' => [
                'type' => self::UINT,
                // \XF::phrase('tlg_please_enter_valid_end_date')
                'required' => 'tlg_please_enter_valid_end_date'
            ],

            'timezone' => ['type' => self::STR, 'default' => '', 'maxLength' => 25],
            'cover_date' => ['type' => self::UINT, 'default' => 0],

            'address' => ['type' => self::STR, 'maxLength' => 150, 'default' => ''],
            'latitude' => ['type' => self::FLOAT, 'default' => 0],
            'longitude' => ['type' => self::FLOAT, 'default' => 0],
            'tags' => ['type' => self::JSON_ARRAY, 'default' => []],
            'location_type' => [
                'type' => self::STR,
                'allowedValues' => [
                    self::LOCATION_TYPE_VIRTUAL,
                    self::LOCATION_TYPE_REAL,
                    self::LOCATION_TYPE_NONE
                ],
                'default' => XF::app()->options()->tl_groups_defaultLocationType,
            ],
            'virtual_address' => ['type' => self::STR, 'maxLength' => 255, 'default' => ''],
            'cancelled_date' => ['type' => self::UINT, 'default' => 0],
            'max_attendees' => ['type' => self::UINT, 'default' => 0],
            'attendee_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
        ];

        $structure->getters = [
            'display_address' => true,
        ];

        $structure->behaviors = [
            'XF:Taggable' => ['stateField' => ''],
            'XF:Indexable' => [
                'checkForUpdates' => ['event_name', 'group_id', 'user_id', 'tags', 'first_comment_id']
            ],
            'XF:IndexableContainer' => [
                'childContentType' => App::CONTENT_TYPE_COMMENT,
                'childIds' => function ($event) {
                    if (!$event instanceof Event) {
                        return [];
                    }

                    return $event->db()->fetchAllColumn('
                        SELECT comment_id
                        FROM xf_tl_group_comment
                        WHERE content_type = ? AND content_id = ?
                    ', ['event', $event->event_id]);
                },
                'checkForUpdates' => ['group_id']
            ],

            'Truonglv\Groups:Countable' => [
                'countField' => 'event_count',
                'relationName' => 'Group',
                'relationKey' => 'group_id'
            ],
        ];

        $structure->relations = [
            'Watched' => [
                'type' => self::TO_MANY,
                'entity' => 'Truonglv\Groups:EventWatch',
                'conditions' => 'event_id',
                'key' => 'user_id'
            ],
            'Guests' => [
                'type' => self::TO_MANY,
                'entity' => 'Truonglv\Groups:EventGuest',
                'conditions' => 'event_id',
                'key' => 'user_id'
            ]
        ];

        self::addCommentStructureElements($structure);

        $structure->withAliases = [
            'full' => [
                'Group',
                'Group.full',
                'FirstComment',
                'User',
                'User.Profile',
                'User.Privacy',
                function () {
                    $visitor = XF::visitor();
                    if ($visitor->user_id <= 0) {
                        return null;
                    }

                    return [
                        'Guests|' . $visitor->user_id,
                        'Watched|' . $visitor->user_id
                    ];
                }
            ],
            'api' => [
                'Group',
                'User'
            ]
        ];

        $structure->defaultWith = ['Group'];

        return $structure;
    }

    /**
     * @param mixed $latitude
     * @return bool
     */
    protected function verifyLatitude(& $latitude)
    {
        return $this->roundFloatValue($latitude);
    }

    /**
     * @param mixed $longitude
     * @return bool
     */
    protected function verifyLongitude(& $longitude)
    {
        return $this->roundFloatValue($longitude);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function roundFloatValue(& $value)
    {
        $value = round($value, 6);

        return true;
    }

    protected function _preSave()
    {
        if ($this->isChanged('begin_date') || $this->isChanged('end_date')) {
            if ($this->begin_date <= 0
                || $this->end_date <= 0
                || $this->begin_date >= $this->end_date
            ) {
                $this->error(XF::phrase('tlg_please_enter_valid_date'), 'begin_date');
            }
        }
    }

    protected function _postDelete()
    {
        $db = $this->db();

        $db->delete('xf_tl_group_event_watch', 'event_id = ?', $this->event_id);
        $db->delete('xf_tl_group_event_guest', 'event_id = ?', $this->event_id);

        /** @var \XF\Repository\UserAlert $userAlertRepo */
        $userAlertRepo = $this->repository('XF:UserAlert');
        $userAlertRepo->fastDeleteAlertsForContent($this->getEntityContentType(), $this->event_id);

        $this->deleteAllComments('event', $this->event_id);
    }

    protected function rebuildCountersInternal(): void
    {
        $this->attendee_count = (int) $this->db()->fetchOne('
            SELECT COUNT(*)
            FROM `xf_tl_group_event_guest`
            WHERE `event_id` = ?
                AND `intend` = ?
                AND `user_id` <> ?
        ', [
            $this->event_id,
            EventGuest::INTEND_GOING,
            $this->user_id
        ]);
    }
}
