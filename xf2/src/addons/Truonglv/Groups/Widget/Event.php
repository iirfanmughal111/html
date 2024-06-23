<?php

namespace Truonglv\Groups\Widget;

use XF;
use function array_keys;
use XF\Widget\AbstractWidget;

class Event extends AbstractWidget
{
    use WidgetCachable;

    /**
     * @var array
     */
    protected $defaultOptions = [
        'cache_ttl' => 300,
        'limit' => 10,
        'type' => 'ongoing',
        'events_user_groups' => false,
    ];

    /**
     * @return string
     */
    public function render()
    {
        $options = $this->options;
        if ($options['events_user_groups'] === true
            && XF::visitor()->user_id <= 0
        ) {
            return '';
        }

        $eventIds = null;
        if ($options['cache_ttl'] > 0) {
            $eventIds = $this->getCacheData();
        }

        /** @var \Truonglv\Groups\Finder\Event $finder */
        $finder = $this->finder('Truonglv\Groups:Event');
        $finder->with('full');

        if ($eventIds === null) {
            if ($options['type'] === 'upcoming') {
                $finder->upcoming();
            } elseif ($options['type'] === 'ongoing') {
                $finder->ongoing();
            }

            $finder->order('begin_date');
            $finder->limit($options['limit'] * 2);
        } else {
            $finder->whereIds($eventIds);
        }

        $events = $finder->fetch()->filterViewable();
        if ($eventIds !== null) {
            $events = $events->sortByList($eventIds);
        }
        if ($options['events_user_groups'] === true) {
            $events = $events->filter(function (\Truonglv\Groups\Entity\Event $event) {
                /** @var \Truonglv\Groups\Entity\Group $group */
                $group = $event->Group;

                return $group->Member !== null;
            });
        }

        if ($eventIds === null
            && $events->count() > 0
            && $options['cache_ttl'] > 0
        ) {
            $eventIds = $events->keys();
            $this->saveData($eventIds, $options['cache_ttl']);
        }

        if ($events->count() > $options['limit']) {
            $events = $events->slice(0, $options['limit']);
        }

        $params = [
            'events' => $events,
            'title' => $this->getTitle(),
            'options' => $options
        ];

        return $this->renderer('tlg_widget_event', $params);
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
            'cache_ttl' => 'uint',
            'limit' => 'uint',
            'type' => 'str',
            'events_user_groups' => 'bool'
        ]);

        if (!in_array($options['type'], ['upcoming', 'ongoing'], true)) {
            $options['type'] = 'ongoing';
        }

        if ($options['limit'] < 1) {
            $options['limit'] = 1;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getOptionsTemplate()
    {
        return 'admin:tlg_widget_def_options_event';
    }

    protected function getCacheId(): string
    {
        $options = $this->options;
        $visitor = XF::visitor();
        foreach (array_keys($options) as $key) {
            if (!isset($this->defaultOptions[$key])) {
                unset($options[$key]);
            }
        }

        if ($options['events_user_groups'] === true) {
            $options['user_id'] = $visitor->user_id;
        }

        return md5($this->widgetConfig->widgetKey . $this->widgetConfig->widgetId . serialize($options));
    }
}
