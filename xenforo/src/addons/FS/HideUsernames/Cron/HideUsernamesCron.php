<?php

namespace FS\HideUsernames\Cron;

class HideUsernamesCron
{
    public static function randomNamese()
    {
        $app = \xf::app();

        $service = $app->service('FS\HideUsernames:HideUserNames');

        $service->genrateRandomNames();
    }
}
