<?php

namespace Truonglv\Groups\Repository;

use XF;
use XF\Mvc\Entity\Repository;

class Log extends Repository
{
    /**
     * @param null|int $cutOff
     * @return void
     */
    public function pruneOldLogs($cutOff = null)
    {
        $cutOff = $cutOff !== null ? $cutOff : (XF::$time - 30 * 86400);

        $this->db()
            ->delete('xf_tl_group_action_log', 'log_date <= ?', $cutOff);
    }
}
