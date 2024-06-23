<?php

namespace Truonglv\Groups\Cron;

use XF;
use Truonglv\Groups\App;

class Auto
{
    /**
     * @return void
     */
    public static function runHourly()
    {
        if (XF::app()->options()->tl_groups_eventReminder > 0) {
            XF::app()->jobManager()
                ->enqueueLater(
                    'tlg_eventReminder',
                    XF::$time,
                    'Truonglv\Groups:EventReminder'
                );
        }

        App::groupRepo()->batchUpdateGroupViews();
        App::groupRepo()->cleanUpInactiveGroups();

        App::resourceRepo()->batchUpdateResourceViews();
        App::resourceRepo()->batchUpdateResourceDownloads();
    }
}
