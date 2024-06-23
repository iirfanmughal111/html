<?php

namespace FS\HideUsernames\Job;

use XF\Job\AbstractJob;

class RandomUsername extends AbstractJob
{

    protected $defaultData = [];

    public function run($maxRunTime)
    {


        $app = \xf::app();

        $serviceHide = $app->service('FS\HideUsernames:HideUserNames');

        $serviceHide->genrateRandomNames();

        return $this->complete();
    }

    public function getStatusMessage()
    {
    }

    public function canCancel()
    {
        return true;
    }

    public function canTriggerByChoice()
    {
        return true;
    }
}
