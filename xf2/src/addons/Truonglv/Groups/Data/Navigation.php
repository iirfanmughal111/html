<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Data;

use XF;
use function uasort;
use Truonglv\Groups\App;
use function array_replace;

class Navigation
{
    const DEFAULT_FIELD_ORDER = 999;

    const TAB_ABOUT = 'about';
    const TAB_NEWS_FEEDS = 'newsFeed';
    const TAB_DISCUSSIONS = 'discussions';
    const TAB_MEMBERS = 'members';
    const TAB_EVENTS = 'events';
    const TAB_RESOURCES = 'resources';
    const TAB_MEDIA = 'media';

    /**
     * @var array
     */
    protected $navCache = [];

    public function getNavigationTabOptions(): array
    {
        $tabs = [
            static::TAB_NEWS_FEEDS => XF::phraseDeferred('tlg_news_feed'),
            static::TAB_DISCUSSIONS => XF::phraseDeferred('tlg_discussions'),
            static::TAB_MEMBERS => XF::phraseDeferred('tlg_members'),
            static::TAB_EVENTS => XF::phraseDeferred('tlg_events'),
            static::TAB_RESOURCES => XF::phraseDeferred('tlg_resources'),
        ];

        $addOns = XF::app()->container('addon.cache');
        if (isset($addOns['XFMG'])) {
            // @phpstan-ignore-next-line
            $tabs[static::TAB_MEDIA] = XF::phraseDeferred('xfmg_media');
        }

        return $tabs;
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return array
     */
    public function getNavigationItems(\Truonglv\Groups\Entity\Group $group)
    {
        if (isset($this->navCache[$group->group_id])) {
            return $this->navCache[$group->group_id];
        }

        $navItems = [];
        $router = XF::app()->router('public');

        $navItems[static::TAB_ABOUT] = [
            'title' => XF::phraseDeferred('tlg_about'),
            'link' => $router->buildLink('groups/about', $group),
            'order' => 0
        ];

        if ($group->canViewContent()) {
            $defaultTab = $group->getDefaultTab();
            $disabledNavTabs = $group->getDisabledNavigationTabs();

            if (!in_array(static::TAB_NEWS_FEEDS, $disabledNavTabs, true)) {
                $navItems[static::TAB_NEWS_FEEDS] = [
                    'title' => XF::phraseDeferred('tlg_news_feed'),
                    'link' => $router->buildLink('groups' . ($defaultTab === static::TAB_NEWS_FEEDS ? '' : '/feeds'), $group),
                    'order' => 5
                ];
            }

            if (!in_array(static::TAB_DISCUSSIONS, $disabledNavTabs, true) && $group->canViewForums()) {
                $navItems[static::TAB_DISCUSSIONS] = [
                    'title' => XF::phraseDeferred('tlg_discussions'),
                    'link' => $router->buildLink('groups' . ($defaultTab === static::TAB_DISCUSSIONS ? '' : '/discussions'), $group),
                    'order' => 10
                ];
            }

            if (!in_array(static::TAB_MEMBERS, $disabledNavTabs, true)) {
                $navItems[static::TAB_MEMBERS] = [
                    'title' => XF::phraseDeferred('tlg_members'),
                    'link' => $router->buildLink('groups' . ($defaultTab === static::TAB_MEMBERS ? '' : '/members'), $group),
                    'order' => 20
                ];
            }

            if ($group->canApproveMembers()) {
                $navItems['members_moderated'] = [
                    'title' => XF::phraseDeferred('tlg_members_waiting_approval'),
                    'link' => $router->buildLink('groups/members', $group, ['member_state' => App::MEMBER_STATE_MODERATED]),
                    'order' => 25,
                    'counter' => $group->member_moderated_count
                ];
            }

            if ($group->canViewEvents() && !in_array(static::TAB_EVENTS, $disabledNavTabs, true)) {
                $navItems[static::TAB_EVENTS] = [
                    'title' => XF::phraseDeferred('tlg_events'),
                    'link' => $router->buildLink('groups' . ($defaultTab === static::TAB_EVENTS ? '' : '/events'), $group),
                    'order' => 30
                ];
            }

            if ($group->canViewResources() && !in_array(static::TAB_RESOURCES, $disabledNavTabs, true)) {
                $navItems[static::TAB_RESOURCES] = [
                    'title' => XF::phraseDeferred('tlg_resources'),
                    'link' => $router->buildLink('groups' . ($defaultTab === static::TAB_RESOURCES ? '' : '/resources'), $group),
                    'order' => 50
                ];
            }

            foreach ($group->getExtraFieldTabs() as $fieldId => $title) {
                $navItems['field_' . $fieldId] = [
                    'title' => $title,
                    'link' => $router->buildLink('groups/field', $group, ['field' => $fieldId]),
                    'order' => self::DEFAULT_FIELD_ORDER
                ];
            }
        }

        $navItems = array_replace($navItems, $this->getExtraNavigationItems($group));
        $this->navCache[$group->group_id] = $this->sortItems($navItems);

        return $navItems;
    }

    /**
     * @param array $items
     * @return array
     */
    protected function sortItems(array $items)
    {
        uasort($items, function ($a, $b) {
            return $a['order'] - $b['order'];
        });

        return $items;
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return array
     */
    protected function getExtraNavigationItems(\Truonglv\Groups\Entity\Group $group)
    {
        $extra = [];
        $router = XF::app()->router('public');

        if ($group->canViewContent()) {
            if (App::isEnabledXenMediaAddOn() && !in_array(static::TAB_MEDIA, $group->getDisabledNavigationTabs(), true)) {
                $extra[static::TAB_MEDIA] = [
                    // @phpstan-ignore-next-line
                    'title' => XF::phraseDeferred('xfmg_media'),
                    'link' => $router->buildLink('groups' . ($group->getDefaultTab() === static::TAB_MEDIA ? '' : '/media'), $group),
                    'order' => 40
                ];
            }
        }

        return $extra;
    }
}
