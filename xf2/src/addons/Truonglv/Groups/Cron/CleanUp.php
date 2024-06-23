<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Cron;

use XF;
use Truonglv\Groups\App;

class CleanUp
{
    /**
     * @throws \XF\Db\Exception
     * @throws \XF\PrintableException
     * @return void
     */
    public static function everyTwoHours()
    {
        App::groupRepo()->pruneExpiredFeatureGroups();

        App::memberRepo()->pruneExpiredBanningRecords();
        App::logRepo()->pruneOldLogs();
    }

    /**
     * @return void
     */
    public static function runEveryFiveMinutes()
    {
        $db = XF::db();
        $db->query('
            UPDATE `xf_tl_group` AS `group`
                INNER JOIN `xf_tl_group_activity` AS `activity`
                    ON (`activity`.`group_id` = `group`.`group_id`)
            SET `group`.`last_activity` = 
                IF (`group`.`last_activity` > `activity`.`activity_date`, `group`.`last_activity`, `activity`.`activity_date`)
        ');
        $db->emptyTable('xf_tl_group_activity');
    }
}
