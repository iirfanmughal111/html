<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Widget;

use XF;
use Exception;
use function md5;
use function count;
use LogicException;
use function in_array;
use XF\Repository\User;
use function array_keys;
use Truonglv\Groups\App;
use function json_encode;
use XF\Widget\AbstractWidget;
use XF\Mvc\Entity\ArrayCollection;

class Group extends AbstractWidget
{
    use WidgetCachable;

    /**
     * @var array
     */
    protected $defaultOptions = [
        'limit' => 5,
        'style' => 'simple',
        'order' => 'last_activity',
        'direction' => 'desc',
        'itemsPerRow' => 3,
        'category_ids' => [],
        'max_members' => 5,
        'featured_only' => 0,
        'custom_template' => '',

        'ttl' => 5,
        'user_groups_only' => false,
        'use_guest' => false
    ];

    /**
     * @param mixed $context
     * @return array
     */
    protected function getDefaultTemplateParams($context)
    {
        $params = parent::getDefaultTemplateParams($context);
        if ($context == 'options') {
            $categoryRepo = App::categoryRepo();
            $params['nodeTree'] = $categoryRepo->createCategoryTree();
        }

        return $params;
    }

    /**
     * @return string
     */
    public function getOptionsTemplate()
    {
        return 'admin:tlg_widget_def_options_group';
    }

    /**
     * @return string|\XF\Widget\WidgetRenderer
     * @throws Exception
     */
    public function render()
    {
        $options = $this->options;
        $groupIds = null;

        if ($options['ttl'] > 0) {
            $groupIds = $this->getCacheData();
        }

        if ($groupIds === null) {
            $groupIds = $this->getGroupIdsForCache();
            if ($options['ttl'] > 0) {
                $this->saveData($groupIds, $options['ttl'] * 60);
            }
        }

        if (count($groupIds) === 0) {
            return '';
        }

        $finder = App::groupFinder();

        $finder->with('full');
        $finder->where('group_id', $groupIds);
        $finder->where('group_state', 'visible');

        $groups = $finder->fetch()->sortByList($groupIds);

        /** @var User $userRepo */
        $userRepo = $this->repository('XF:User');
        $visitor = ($options['use_guest'] && !$options['user_groups_only'])
            ? $userRepo->getGuestUser()
            : XF::visitor();

        /** @var ArrayCollection $groups */
        $groups = XF::asVisitor($visitor, function () use ($groups) {
            return $groups->filterViewable();
        });

        if ($groups->count() > $options['limit']) {
            $groups = $groups->slice($options['limit']);
        }

        $showMembers = false;
        if ($options['max_members'] > 0 && $options['style'] === 'full') {
            $showMembers = true;
            App::groupRepo()->addMembersIntoGroups($groups, $options['max_members']);
        }

        $viewParams = [
            'title' => $this->getTitle(),
            'style' => $options['style'],
            'groups' => $groups,
            'itemsPerRow' => $options['itemsPerRow'],
            'showMembers' => $showMembers,
            'options' => $options
        ];

        return $this->renderer(
            $options['custom_template'] === '' ? 'tlg_widget_group' : $options['custom_template'],
            $viewParams
        );
    }

    /**
     * @param \XF\Http\Request $request
     * @param array $options
     * @param mixed $error
     * @return bool
     */
    public function verifyOptions(\XF\Http\Request $request, array & $options, & $error = null)
    {
        $options = $request->filter([
            'limit' => 'uint',
            'style' => 'str',
            'order' => 'str',
            'category_ids' => 'array-uint',
            'itemsPerRow' => 'uint',
            'max_members' => 'uint',
            'featured_only' => 'uint',
            'custom_template' => 'str',
            'user_groups_only' => 'bool',
            'ttl' => 'uint',
            'use_guest' => 'bool',
        ]);

        if (in_array(0, $options['category_ids'], true)) {
            $options['category_ids'] = [];
        }

        if ($options['limit'] < 1) {
            $options['limit'] = 1;
        }

        if ($options['itemsPerRow'] < 1) {
            $options['itemsPerRow'] = 1;
        } elseif ($options['itemsPerRow'] > 5) {
            $options['itemsPerRow'] = 5;
        }

        if ($options['max_members'] > 6) {
            $options['max_members'] = 6;
        }

        return true;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getGroupIdsForCache()
    {
        $finder = App::groupFinder();
        $options = $this->options;

        $finder->with('Members|' . XF::visitor()->user_id);

        $this->applyGroupFilters($finder);

        $finder->limit($options['limit'] * 3);

        /** @var User $userRepo */
        $userRepo = $this->repository('XF:User');
        $visitor = ($options['use_guest'] && !$options['user_groups_only'])
            ? $userRepo->getGuestUser()
            : XF::visitor();

        $groups = $finder->fetch();
        /** @var ArrayCollection $groups */
        $groups = XF::asVisitor($visitor, function () use ($groups) {
            return $groups->filterViewable();
        });

        if ($groups->count() > $this->options['limit']) {
            $groups = $groups->slice(0, $this->options['limit']);
        }

        return $groups->keys();
    }

    /**
     * @param \Truonglv\Groups\Finder\Group $groupFinder
     * @return void
     */
    protected function applyGroupFilters(\Truonglv\Groups\Finder\Group $groupFinder)
    {
        $options = $this->options;

        if ($options['category_ids']) {
            $groupFinder->where('category_id', $options['category_ids']);
        }

        $groupFinder->where('group_state', 'visible');

        $availableSorts = App::groupRepo()->getAvailableGroupSorts();

        if ($options['featured_only']) {
            $groupFinder->with('Feature');

            $groupFinder->whereOr(
                ['Feature.expire_date', 0],
                ['Feature.expire_date', '>', XF::$time]
            );
        }

        $visitor = XF::visitor();
        if ($options['user_groups_only']) {
            $groupFinder->where('Members|' . $visitor->user_id . '.member_state', App::MEMBER_STATE_VALID);
        } else {
            $groupFinder->applyGlobalPrivacyChecks();
        }

        if ($options['order'] !== '' && isset($availableSorts[$options['order']])) {
            $direction = $this->options['direction'] !== '' ? $this->options['direction'] : 'desc';
            $groupFinder->order($availableSorts[$options['order']], $direction);
        }
    }

    protected function getCacheId(): string
    {
        $options = $this->options;
        unset(
            $options['use_guest'],
            $options['style'],
            $options['custom_template'],
            $options['max_members'],
            $options['itemsPerRow']
        );
        foreach (array_keys($options) as $key) {
            if (!isset($this->defaultOptions[$key])) {
                unset($options[$key]);
            }
        }

        $encoded = json_encode($options);
        if ($encoded === false) {
            throw new LogicException('Encode failed');
        }

        return md5($this->widgetConfig->widgetKey . $this->widgetConfig->widgetId . $encoded);
    }
}
