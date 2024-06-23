<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF;
use XF\Timer;
use XF\Job\AbstractJob;
use Truonglv\Groups\App;
use Truonglv\Groups\XF\Entity\Forum;

class NodeParent extends AbstractJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'old_node_id' => 0,
        'new_node_id' => 0,
        'lastRunId' => 0
    ];

    /**
     * @return bool
     */
    public function canTriggerByChoice()
    {
        return true;
    }

    /**
     * @param mixed $maxRunTime
     * @return \XF\Job\JobResult
     * @throws \XF\PrintableException
     */
    public function run($maxRunTime)
    {
        $timer = new Timer($maxRunTime);

        $finder = XF::finder('XF:Node');

        $finder->where('node_id', '>', $this->data['lastRunId']);
        $finder->order('node_id');

        $nodes = $finder->limit(50)->fetch();
        if ($nodes->count() <= 0) {
            return $this->complete();
        }

        /** @var \Truonglv\Groups\XF\Entity\Node $node */
        foreach ($nodes as $node) {
            if ($timer->limitExceeded()) {
                break;
            }

            $this->data['lastRunId'] = $node->node_id;
            if ($node->node_type_id === App::NODE_TYPE_ID
                && $node->parent_node_id == $this->data['old_node_id']
            ) {
                /** @var Forum|null $forum */
                $forum = $this->app->em()->find('XF:Forum', $node->node_id, 'GroupForum');
                if ($forum !== null && $forum->GroupForum !== null) {
                    $node->parent_node_id = $this->data['new_node_id'];
                    $node->save();
                }
            }
        }

        return $this->resume();
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return '';
    }
}
