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

class CategoryDelete extends AbstractJob
{
    /**
     * @return bool
     */
    public function canTriggerByChoice()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return false;
    }

    /**
     * @param mixed $maxRunTime
     * @return \XF\Job\JobResult
     */
    public function run($maxRunTime)
    {
        if ($this->data['category_id'] <= 0) {
            return $this->complete();
        }

        $timer = new Timer($maxRunTime);

        $categoryId = $this->data['category_id'];
        $groups = App::groupFinder()
            ->where('category_id', $categoryId)
            ->limit(50)
            ->fetch();

        if ($groups->count() <= 0) {
            return $this->complete();
        }

        foreach ($groups as $group) {
            if ($timer->limitExceeded()) {
                break;
            }

            $group->delete();
        }

        return $this->resume();
    }

    /**
     * @return \XF\Phrase
     */
    public function getStatusMessage()
    {
        return XF::phrase('tlg_groups');
    }
}
