<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Pub\Controller;

use XF;
use DateTime;
use Throwable;
use function round;
use function in_array;
use XF\Mvc\Reply\View;
use function preg_match;
use function strtolower;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use function array_merge;
use Truonglv\Groups\Listener;
use XF\ControllerPlugin\Delete;
use XF\Mvc\Reply\AbstractReply;
use Truonglv\Groups\Entity\EventGuest;
use Truonglv\Groups\Entity\EventWatch;
use XF\Pub\Controller\AbstractController;
use Truonglv\Groups\Service\Event\Creator;
use Truonglv\Groups\Service\Event\Intender;

class Event extends AbstractController
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        parent::preDispatchController($action, $params);

        if (!App::hasPermission('view')) {
            throw $this->exception($this->noPermission());
        }
    }

    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @param AbstractReply $reply
     * @return void
     */
    protected function postDispatchController($action, ParameterBag $params, AbstractReply & $reply)
    {
        parent::postDispatchController($action, $params, $reply);

        if (!$reply instanceof View) {
            return;
        }

        /** @var \Truonglv\Groups\Entity\Event|null $event */
        $event = $reply->getParam('event');
        if ($event !== null && $event->Group !== null) {
            $reply->setContainerKey('tlg-group-' . $event->Group->group_id);
            $reply->setContentKey('tlg-group-event-' . $event->event_id);
        }

        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = $reply->getParam('group');
        if (strtolower($action) === 'add' && $group !== null) {
            $reply->setContainerKey('tlg-group-' . $group->group_id);
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params->event_id, $this->getDefaultEventViewWith());
        $tab = $this->filter('tab', 'str');
        if (!in_array($tab, ['information', 'comments', 'guests'], true)) {
            $tab = 'information';
        }

        if ($event->FirstComment === null) {
            return $this->error(XF::phrase('something_went_wrong_please_try_again'));
        }

        $this->assertCanonicalUrl($this->buildLink('group-events', $event));

        $params = $this->getEventIndexParams($event, $tab);

        $params = [
            'tabFilter' => $tab,
            'event' => $event,
            'group' => $event->Group,
            'macroParams' => $params,
            'macroName' => 'tab_' . $tab
        ];

        $view = $this->view('Truonglv\Groups:Event\View', 'tlg_event_view', $params);

        if ($event->Group !== null) {
            Listener::addContentLanguageResponseHeader($event->Group);
            App::groupRepo()->logView($event->Group);
        }

        return $view;
    }

    /**
     * @param \Truonglv\Groups\Entity\Event $event
     * @param string $tab
     * @return mixed
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function getEventIndexParams(\Truonglv\Groups\Entity\Event $event, string $tab)
    {
        $params = [];

        switch ($tab) {
            case 'information':
                /** @var \Truonglv\Groups\Entity\Comment $description */
                $description = $event->FirstComment;
                /** @var \XF\Repository\Attachment $attachmentRepo */
                $attachmentRepo = $this->repository('XF:Attachment');
                $entities = $this->em()->getBasicCollection([
                    $description->comment_id => $description
                ]);
                $attachmentRepo->addAttachmentsToContent($entities, App::CONTENT_TYPE_COMMENT);

                $params += [
                    'description' => $description,
                    'mapApiKey' => App::getOption('googleMapKey')
                ];

                break;
            case 'comments':
                $page = $this->filterPage();
                $perPage = App::getOption('commentsPerPage');

                $totalComments = $event->comment_count;
                $this->assertValidPage($page, $perPage, $totalComments, 'group-events', $event);

                $commentFinder = App::commentRepo()->findCommentsForEventView($event);
                $commentFinder->limitByPage($page, $perPage);

                $comments = $commentFinder->fetch()->filterViewable();
                App::commentRepo()->addContentIntoComments($comments);
                App::commentRepo()->addRecentRepliesIntoComments($comments);

                /** @var \XF\Repository\Attachment $attachmentRepo */
                $attachmentRepo = $this->repository('XF:Attachment');
                $attachmentRepo->addAttachmentsToContent($comments, App::CONTENT_TYPE_COMMENT);

                $lastComment = $comments->last();
                if (!$lastComment) {
                    if ($page > 1) {
                        return $this->redirect($this->buildLink('group-events', $event));
                    } else {
                        // should never really happen
                        return $this->error(XF::phrase('something_went_wrong_please_try_again'));
                    }
                }

                if ($page === 1) {
                    $comments = $comments->filter(function (\Truonglv\Groups\Entity\Comment $comment) use ($event) {
                        return $comment->comment_id !== $event->first_comment_id;
                    });
                }

                $canInlineMod = false;
                /** @var \Truonglv\Groups\Entity\Comment $comment */
                foreach ($comments as $comment) {
                    if ($comment->canUseInlineModeration()) {
                        $canInlineMod = true;

                        break;
                    }
                }

                $attachmentData = null;
                if ($event->canUploadAndManageAttachments()) {
                    /** @var \XF\Repository\Attachment $attachmentRepo */
                    $attachmentRepo = $this->repository('XF:Attachment');
                    $attachmentData = $attachmentRepo->getEditorData(
                        App::CONTENT_TYPE_COMMENT,
                        $event->Group
                    );
                }

                $params += [
                    'attachmentData' => $attachmentData,
                    'canInlineMod' => $canInlineMod,
                    'comments' => $comments,
                    'totalComments' => $totalComments,
                    'perPage' => $perPage,
                    'page' => $page,
                    'pageNavParams' => [
                        'tab' => $tab
                    ]
                ];

                break;
            case 'guests':
                $intends = \Truonglv\Groups\Repository\Event::getIntendOptions();
                $showIntend = $this->filter('intend', 'str');

                $intendPairs = App::eventRepo()->getIntendLabelPairs();

                $limit = 10;
                $page = 0;

                if (in_array($showIntend, $intends, true)) {
                    $intends = [$showIntend];

                    $limit = 20;
                    $page = $this->filterPage();
                }

                $guests = [];
                foreach ($intends as $intend) {
                    $finder = $this->finder('Truonglv\Groups:EventGuest')
                        ->with('User')
                        ->where('event_id', $event->event_id)
                        ->where('intend', $intend);
                    if ($page > 0) {
                        $finder->limitByPage($page, $limit);
                    } else {
                        $finder->limit($limit);
                    }

                    $total = $finder->total();
                    if (!$total) {
                        continue;
                    }

                    $hasMore = false;
                    if ($page > 1) {
                        $hasMore = ($total - $page * $limit) > 0;
                    }

                    $guests[$intend] = [
                        'title' => $intendPairs[$intend],
                        'users' => $finder->fetch(),
                        'nextPage' => $hasMore ? ($page + 1) : null,
                        'total' => $finder->total(),
                    ];
                }

                $params['guests'] = $guests;

                break;
        }

        return $params;
    }

    public function actionCalendar()
    {
        $groupId = $this->filter('group_id', 'uint');
        $group = App::assertionPlugin($this)->assertGroupViewable($groupId);

        $start = $this->filter('start', 'str');
        $end = $this->filter('end', 'str');

        try {
            $startDt = DateTime::createFromFormat(DateTime::ISO8601, $start);
            $endDt = DateTime::createFromFormat(DateTime::ISO8601, $end);
        } catch (Throwable $e) {
            return $this->noPermission();
        }

        $eventFinder = App::eventFinder();
        $eventFinder->with('full');
        $eventFinder->inGroup($group);
        $eventFinder->whereOr([
            ['begin_date', 'BETWEEN', [$startDt->getTimestamp(), $endDt->getTimestamp()]],
            ['end_date', 'BETWEEN', [$startDt->getTimestamp(), $endDt->getTimestamp()]]
        ]);

        $eventFinder->order('begin_date');
        $events = $eventFinder->fetch()->filterViewable();

        $view = $this->view('Truonglv\Groups:Event\Calendar', '', [
            'group' => $group,
            'events' => App::eventRepo()->getEventsDataForCalendar($events, $startDt->getTimezone()),
            'eventsData' => $events,
        ]);
        $view->setViewOption('skipDefaultJsonParams', true);

        return $view;
    }

    public function actionQuickEdit(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params['event_id']);
        if (!$event->canEdit($error)) {
            return $this->noPermission($error);
        }

        $sub = $this->filter([
            'years' => 'int',
            'months' => 'int',
            'days' => 'int',
            'milliseconds' => 'int'
        ]);

        $beginDate = new DateTime('@' . $event->begin_date);
        /** @var DateTime|null $endDate */
        $endDate = null;
        if ($event->end_date > 0) {
            $endDate = new DateTime('@' . $event->end_date);
        }

        foreach ($sub as $unit => $value) {
            if ($unit === 'milliseconds') {
                $value = intval($value / 1000);
            }

            if ($value === 0) {
                continue;
            }

            $prefix = $value > 0 ? '+' : '-';
            $suffix = $unit;
            if ($unit === 'milliseconds') {
                $suffix = 'seconds';
            }
            $suffix = abs($value) > 1 ? $suffix : substr($suffix, 0, -1);

            $beginDate->modify($prefix . ' ' . abs($value) . ' ' . $suffix);
            if ($endDate !== null) {
                $endDate->modify($prefix . ' ' . abs($value) . ' ' . $suffix);
            }
        }

        $event->begin_date = $beginDate->getTimestamp();
        $event->end_date = $endDate !== null ? $endDate->getTimestamp() : 0;
        $event->save();

        $view = $this->message(XF::phrase('changes_saved'));
        $view->setJsonParam('is_saved', true);

        return $view;
    }

    public function actionTags(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params->event_id);

        return App::eventListPlugin($this)->actionTags(
            'Truonglv\Groups:Event\Tags',
            App::CONTENT_TYPE_EVENT,
            $event,
            $this->buildLink('group-events/tags', $event),
            $this->buildLink('group-events', $event)
        );
    }

    public function actionAdd(ParameterBag $params)
    {
        $group = App::assertionPlugin($this)->assertGroupViewable($this->filter('group_id', 'uint'));
        if (!$group->canAddEvent($errors)) {
            return $this->noPermission($errors);
        }

        // to update session activity
        $params->offsetSet('group_id', $group->group_id);

        if ($this->isPost()) {
            $event = $this->saveEventProcess($group);

            App::eventRepo()->watchEvent($event);

            return $this->redirect($this->buildLink('group-events', $event));
        }

        $attachmentData = null;
        if ($group->canUploadAndManageAttachments()) {
            /** @var \XF\Repository\Attachment $attachmentRepo */
            $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentData = $attachmentRepo->getEditorData(
                App::CONTENT_TYPE_COMMENT,
                $group
            );
        }

        $date = $this->filter('date', 'str');
        $event = $group->getNewEvent();

        if ($date !== '' && preg_match('/\d{4}\-\d{0,2}\-\d{0,2}/', $date) === 1) {
            $dt = DateTime::createFromFormat('Y-m-d', $date);
            if ($dt !== false) {
                $event->begin_date = (int) $dt->format('U');
            }
        }

        if ($event->begin_date <= 0) {
            $event->begin_date = XF::$time;
        }

        // 1 day
        $event->end_date = $event->begin_date + 86400;

        $viewParams = [
            'group' => $group,
            'event' => $event,
            'canEditTags' => $group->canEditTags(),
            'attachmentData' => $attachmentData,
        ];

        return $this->getEventForm('Truonglv\Groups:Event\Add', $viewParams);
    }

    public function actionEdit(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params->event_id);

        $this->assertCanonicalUrl($this->buildLink('group-events/edit', $event));

        $error = null;
        if (!$event->canEdit($error)) {
            return $this->noPermission($error);
        }

        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $event->Group;

        if ($this->isPost()) {
            $event = $this->saveEventProcess($group, $event);

            return $this->redirect(
                $this->buildLink('group-events', $event)
            );
        }

        $attachmentData = null;
        if ($group->canUploadAndManageAttachments()) {
            /** @var \XF\Repository\Attachment $attachmentRepo */
            $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentData = $attachmentRepo->getEditorData(
                App::CONTENT_TYPE_COMMENT,
                $event->FirstComment
            );
        }

        $uneditableTags = null;
        $editableTags = null;
        $canEditTags = false;

        if ($event->canEditTags()) {
            /** @var \XF\Service\Tag\Changer $tagger */
            $tagger = $this->service('XF:Tag\Changer', App::CONTENT_TYPE_EVENT, $event);

            $grouped = $tagger->getExistingTagsByEditability();

            $editableTags = $grouped['editable'];
            $uneditableTags = $grouped['uneditable'];

            $canEditTags = true;
        }

        if ($event->end_date <= 0) {
            $event->end_date = $event->begin_date + 86400;
        }

        $viewParams = [
            'group' => $event->Group,
            'event' => $event,
            'canEditTags' => $canEditTags,
            'uneditableTags' => $uneditableTags,
            'editableTags' => $editableTags,
            'attachmentData' => $attachmentData
        ];

        return $this->getEventForm('Truonglv\Groups:Event\Edit', $viewParams);
    }

    public function actionWatch(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params->event_id);
        $error = null;
        if (!$event->canWatchUnwatch($error)) {
            return $this->noPermission($error);
        }

        /** @var EventWatch|null $watched */
        $watched = $event->Watched[XF::visitor()->user_id];
        if ($watched !== null) {
            $watched->delete();
        } else {
            App::eventRepo()->watchEvent($event);
        }

        return $this->redirect($this->buildLink('group-events', $event));
    }

    public function actionComment(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params->event_id);

        /** @var \Truonglv\Groups\ControllerPlugin\Comment $commentPlugin */
        $commentPlugin = $this->plugin('Truonglv\Groups:Comment');
        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $event->Group;

        return $commentPlugin->actionComment($event, $group, [
            'expandedLayout' => true,
        ]);
    }

    public function actionIntend(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params->event_id);
        $error = null;

        if (!$event->canIntend($error)) {
            return $this->noPermission($error);
        }

        $visitor = XF::visitor();
        /** @var EventGuest|null $guest */
        $guest = $event->Guests[$visitor->user_id];
        $intends = App::eventRepo()->getIntendLabelPairs();

        $isQuickIntend = (bool) $this->filter('quick', 'bool');

        if ($this->isPost() || $isQuickIntend) {
            /** @var Intender $intender */
            $intender = $this->service('Truonglv\Groups:Event\Intender', $event);
            $intend = $isQuickIntend ? 'going' : $this->filter('intend', 'str');
            $intender->setIntend($intend);

            if (!$intender->validate($errors)) {
                return $this->error($errors);
            }

            $intender->save();
            $intender->sendNotifications();

            return $this->redirect($this->buildLink('group-events', $event));
        }

        return $this->view('Truonglv\Groups:Event\Intend', 'tlg_event_intend', [
            'event' => $event,
            'group' => $event->Group,
            'quickIntend' => $this->filter('_xfWithData', 'bool'),
            'intends' => $intends,
            'guest' => $guest
        ]);
    }

    public function actionCancel(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params->event_id);
        if (!$event->canCancel($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $event->cancelled_date = XF::$time;
            $event->save();

            return $this->redirect($this->buildLink('group-events', $event));
        }

        return $this->view(
            'Truonglv\Groups:Event\Cancel',
            'tlg_event_cancel',
            [
                'event' => $event,
                'group' => $event->Group,
                'enableWrapper' => $this->filter('_xfWithData', 'bool') === false,
            ]
        );
    }

    public function actionUncancel(ParameterBag $params)
    {
        $event = $this->assertEventViewable($params->event_id);
        if (!$event->canCancel($error)) {
            return $this->noPermission($error);
        }

        $event->cancelled_date = 0;
        $event->save();

        return $this->redirect($this->buildLink('group-events', $event));
    }

    public function actionDelete(ParameterBag $params)
    {
        $event = App::assertionPlugin($this)->assertEventViewable($params);
        $error = null;
        if (!$event->canDelete($error)) {
            return $this->noPermission($error);
        }

        /** @var Delete $deletePlugin */
        $deletePlugin = $this->plugin('XF:Delete');

        return $deletePlugin->actionDelete(
            $event,
            $this->buildLink('group-events/delete', $event),
            $this->buildLink('group-events/edit', $event),
            $this->buildLink('groups/events', $event->Group),
            $event->event_name
        );
    }

    protected function getEventForm(string $viewName, array $params, string $template = 'tlg_event_add'): AbstractReply
    {
        /** @var \Truonglv\Groups\Entity\Event $event */
        $event = $params['event'];

        $eventTimeZone = App::getOption('eventTimeZone');
        $canEditTimeZone = App::getOption('eventTimeZoneEdit');
        if ($event->isInsert()) {
            if ($eventTimeZone === 'system') {
                $event->timezone = $this->options()->guestTimeZone;
            } elseif ($eventTimeZone === 'visitor') {
                $event->timezone = XF::visitor()->timezone;
            } else {
                $event->timezone = $eventTimeZone;
            }
        }

        $params = array_merge([
            'showWrapper' => $this->filter('_xfWithData', 'bool') === false,
            'canEditTimeZone' => $canEditTimeZone,
        ], $params);

        return $this->view($viewName, $template, $params);
    }

    /**
     * @return array
     */
    protected function getEventInputData()
    {
        $data = $this->filter([
            'event_name' => 'str',
            'timezone' => 'str',
            'location_type' => 'str',
            'max_attendees' => 'uint',
        ]);

        if ($data['location_type'] === \Truonglv\Groups\Entity\Event::LOCATION_TYPE_REAL) {
            $data['address'] = $this->filter('address', 'str');
            $data['latitude'] = round(
                $this->filter('latitude', 'float'),
                6
            );
            $data['longitude'] = round(
                $this->filter('longitude', 'float'),
                6
            );
        } elseif ($data['location_type'] === \Truonglv\Groups\Entity\Event::LOCATION_TYPE_VIRTUAL) {
            $data['virtual_address'] = $this->filter('virtual_address', 'str');
        }

        return $data;
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param \Truonglv\Groups\Entity\Event|null $event
     * @return \Truonglv\Groups\Entity\Event
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function saveEventProcess(
        \Truonglv\Groups\Entity\Group $group,
        \Truonglv\Groups\Entity\Event $event = null
    ) {
        if ($event !== null) {
            /** @var \Truonglv\Groups\Service\Event\Editor $service */
            $service = $this->service('Truonglv\Groups:Event\Editor', $event);
        } else {
            /** @var Creator $service */
            $service = $this->service('Truonglv\Groups:Event\Creator', $group);
        }

        if ($group->canEditTags()) {
            $service->setTags($this->filter('tags', 'str'));
        }

        $inputData = $this->getEventInputData();
        $canEditTimeZone = (bool) App::getOption('eventTimeZoneEdit');
        if (!$canEditTimeZone) {
            $inputData['timezone'] = '_option';
        }

        $service->getEvent()->bulkSet($inputData);

        $service->getEventPreparer()->setEventDateRaw(
            'begin_date',
            $this->filter('begin_date', [
                'date' => 'str',
                'hour' => 'str',
            ])
        );
        $service->getEventPreparer()->setEventDateRaw(
            'end_date',
            $this->filter('end_date', [
                'date' => 'str',
                'hour' => 'str',
            ])
        );

        /** @var \Truonglv\Groups\ControllerPlugin\Comment $commentPlugin */
        $commentPlugin = $this->plugin('Truonglv\Groups:Comment');
        /** @var \Truonglv\Groups\Entity\Event $event */
        $event = $commentPlugin->saveCommentProcess(
            $service,
            $group->canUploadAndManageAttachments(),
            'description'
        );

        if ($service instanceof Creator) {
            $this->onNewEventPublished($service);
        }

        return $event;
    }

    /**
     * @param Creator $creator
     * @return void
     */
    protected function onNewEventPublished(Creator $creator)
    {
        $creator->sendNotifications();
    }

    /**
     * @return array
     */
    protected function getDefaultEventViewWith()
    {
        return ['full'];
    }

    /**
     * @param mixed $id
     * @param string|array $with
     * @return \Truonglv\Groups\Entity\Event
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertEventViewable($id, $with = 'full')
    {
        return App::assertionPlugin($this)->assertEventViewable($id, $with);
    }

    /**
     * @param array $activities
     * @return array|bool
     */
    public static function getActivityDetails(array $activities)
    {
        return \Truonglv\Groups\ControllerPlugin\Assistant::getActivityDetails($activities);
    }
}
