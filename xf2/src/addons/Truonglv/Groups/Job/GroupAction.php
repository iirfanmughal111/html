<?php

namespace Truonglv\Groups\Job;

use XF;
use LogicException;
use XF\Job\JobResult;
use XF\Job\AbstractJob;
use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Group;

class GroupAction extends AbstractJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'start' => 0,
        'count' => 0,
        'total' => null,
        'criteria' => null,
        'groupIds' => null,
        'actions' => []
    ];

    /**
     * @param mixed $maxRunTime
     * @return JobResult
     * @throws \XF\PrintableException
     */
    public function run($maxRunTime)
    {
        if (is_array($this->data['criteria']) && is_array($this->data['groupIds'])) {
            throw new LogicException('Cannot have both criteria and groupIds values; one must be null');
        }

        $startTime = microtime(true);
        $em = $this->app->em();

        $ids = $this->prepareGroupIds();
        if (count($ids) === 0) {
            return $this->complete();
        }

        $db = $this->app->db();
        $db->beginTransaction();

        $limitTime = ($maxRunTime > 0);
        foreach ($ids as $key => $id) {
            $this->data['count']++;
            $this->data['start'] = $id;
            unset($ids[$key]);

            /** @var Group|null $group */
            $group = $em->find('Truonglv\Groups:Group', $id);
            if ($group !== null) {
                if ((bool) $this->getActionValue('delete')) {
                    $group->delete(false, false);

                    continue; // no further action required
                }

                $this->applyInternalGroupChange($group);
                $group->save(false, false);
            }

            if ($limitTime && microtime(true) - $startTime > $maxRunTime) {
                break;
            }
        }

        if (is_array($this->data['groupIds'])) {
            $this->data['groupIds'] = $ids;
        }

        $db->commit();

        return $this->resume();
    }

    /**
     * @param string $action
     * @return mixed|null
     */
    protected function getActionValue(string $action)
    {
        $value = null;
        if (isset($this->data['actions'][$action])) {
            $value = $this->data['actions'][$action];
        }

        return $value;
    }

    /**
     * @return array
     */
    protected function prepareGroupIds()
    {
        if (is_array($this->data['criteria'])) {
            $searcher = $this->app->searcher('Truonglv\Groups:Group', $this->data['criteria']);
            $results = $searcher->getFinder()
                ->where('group_id', '>', $this->data['start'])
                ->order('group_id')
                ->limit(1000)
                ->fetchColumns('group_id');
            $ids = array_column($results, 'group_id');
        } elseif (is_array($this->data['groupIds'])) {
            $ids = $this->data['groupIds'];
        } else {
            $ids = [];
        }

        sort($ids, SORT_NUMERIC);

        return $ids;
    }

    protected function applyInternalGroupChange(Group $group): void
    {
        $categoryId = $this->getActionValue('category_id');
        if ($categoryId > 0) {
            $group->category_id = $categoryId;
        }

        $newPrivacy = $this->getActionValue('privacy');
        if (in_array($newPrivacy, App::getAllowedPrivacy(), true)) {
            $group->privacy = $newPrivacy;
        }

        if ((bool) $this->getActionValue('approve')) {
            $group->group_state = 'visible';
        }
        if ((bool) $this->getActionValue('unapprove')) {
            $group->group_state = 'moderated';
        }

        if ((bool) $this->getActionValue('soft_delete')) {
            $group->group_state = 'deleted';
        }
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        $actionPhrase = XF::phrase('updating');
        $typePhrase = XF::phrase('tlg_groups');

        if ($this->data['total'] !== null) {
            return sprintf('%s... %s (%d/%d)', $actionPhrase, $typePhrase, $this->data['count'], $this->data['total']);
        }

        return sprintf('%s... %s (%d)', $actionPhrase, $typePhrase, $this->data['start']);
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canTriggerByChoice()
    {
        return false;
    }
}
