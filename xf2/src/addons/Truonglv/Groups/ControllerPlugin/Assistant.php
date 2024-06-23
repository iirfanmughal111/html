<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\ControllerPlugin;

use XF;
use Closure;
use function count;
use Truonglv\Groups\App;
use function is_callable;
use function array_replace;
use function call_user_func;
use InvalidArgumentException;
use XF\ControllerPlugin\AbstractPlugin;

class Assistant extends AbstractPlugin
{
    /**
     * @param string $formTitle
     * @param string $formAction
     * @param int $expireDate
     * @param array $params
     * @return \XF\Mvc\Reply\View
     */
    public function formTimePeriod($formTitle, $formAction, $expireDate, array $params = [])
    {
        $params = array_replace([
            'formTitle' => $formTitle,
            'formAction' => $formAction,
            'expireDate' => $expireDate,
            'submitTitle' => XF::phrase('button.save'),
        ], $params);

        return $this->form('timePeriod', $params['formTitle'], $params['formAction'], $params);
    }

    /**
     * @deprecated
     * @param string $formType
     * @param string $formTitle
     * @param string $formAction
     * @param array $formParams
     * @return \XF\Mvc\Reply\View
     */
    protected function form($formType, $formTitle, $formAction, array $formParams)
    {
        if (!isset($formParams['formAction'])) {
            throw new InvalidArgumentException('Must have `formAction`.');
        }

        if (!isset($formParams['formTitle'])) {
            throw new InvalidArgumentException('Must have `formTitle`.');
        }

        $params = [
            'formType' => $formType,
            'formAction' => $formAction,
            'formTitle' => $formTitle,
            'params' => $formParams
        ];

        if (!isset($formParams['submitTitle'])) {
            $params['submitTitle'] = $formParams['formTitle'];
        } else {
            $params['submitTitle'] = $formParams['submitTitle'];

            unset($formParams['submitTitle']);
        }

        if (isset($formParams['hiddenInputs'])) {
            $params['hiddenInputs'] = $formParams['hiddenInputs'];

            unset($formParams['hiddenInputs']);
        }

        return $this->view(
            'Truonglv\Groups:Assistant\Form',
            'tlg_assistant_form',
            $params
        );
    }

    /**
     * @param array $activities
     * @param Closure|null $fallback
     * @return array|bool
     */
    public static function getActivityDetails(array $activities, Closure $fallback = null)
    {
        $output = [];
        $dataMap = [];
        $activityMap = [];

        $paramKeys = self::getParamKeyOptions();
        /** @var \XF\Entity\SessionActivity $activity */
        foreach ($activities as $key => $activity) {
            foreach ($paramKeys as $paramKey => $options) {
                $value = $activity->pluckParam($paramKey);
                if ($value !== null) {
                    $dataMap[$paramKey][] = $value;
                    $activityMap[$key] = $paramKey;

                    break;
                }
            }
        }

        if (count($dataMap) === 0) {
            // fallback to viewing unknown page...
            return false;
        }

        $loadedData = [];
        foreach ($dataMap as $paramKey => $dataIds) {
            $loadedData[$paramKey] = $paramKeys[$paramKey]['finder']->whereIds($dataIds)
                ->with($paramKeys[$paramKey]['relations'])
                ->fetch()
                ->filterViewable();
        }

        $router = XF::app()->router('public');

        foreach ($activities as $key => $activity) {
            $paramKey = isset($activityMap[$key]) ? $activityMap[$key] : null;
            if ($paramKey !== null) {
                $value = $activity->pluckParam($paramKey);
                $config = $paramKeys[$paramKey];

                if ($value && isset($loadedData[$paramKey][$value])) {
                    /** @var \XF\Mvc\Entity\Entity $entity */
                    $entity = $loadedData[$paramKey][$value];

                    $output[$key] = [
                        'description' => $config['phrase'],
                        'url' => $router->buildLink($config['routeName'], $entity),
                        'title' => null
                    ];
                    if ($config['title']) {
                        if (is_callable($config['title'])) {
                            $output[$key]['title'] = call_user_func($config['title'], $entity);
                        } else {
                            $output[$key]['title'] = $entity->get($config['title']);
                        }
                    }
                } else {
                    $output[$key] = $fallback !== null ? $fallback($activity) : $config['phrase'];
                }
            } else {
                // does not know it
                $output[$key] = false;
            }
        }

        return $output;
    }

    /**
     * @return array
     */
    protected static function getParamKeyOptions()
    {
        return [
            'category_id' => [
                'finder' => App::categoryFinder(),
                'title' => 'category_title',
                'phrase' => XF::phrase('tlg_viewing_group_category'),
                'routeName' => 'group-categories',
                'relations' => []
            ],
            'group_id' => [
                'finder' => App::groupFinder(),
                'title' => 'name',
                'relations' => ['Members|' . XF::visitor()->user_id],
                'phrase' => XF::phrase('tlg_viewing_group'),
                'routeName' => 'groups'
            ],
            'event_id' => [
                'finder' => App::eventFinder(),
                'title' => 'event_name',
                'relations' => ['Group'],
                'phrase' => XF::phrase('tlg_viewing_group_event'),
                'routeName' => 'group-events'
            ],
            'post_id' => [
                'finder' => App::postFinder(),
                'title' => function ($entity) {
                    return $entity->Group->name;
                },
                'relations' => ['Group'],
                'phrase' => XF::phrase('tlg_viewing_group'),
                'routeName' => 'group-posts'
            ]
        ];
    }
}
