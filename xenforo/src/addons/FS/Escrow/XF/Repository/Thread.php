<?php

namespace FS\Escrow\XF\Repository;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
    public function findThreadsWithLatestPosts()
	{
        return  parent::findThreadsWithLatestPosts()->where('node_id','!=',$this->app()->options()->fs_escrow_applicable_forum);
    }

    public function findThreadsWithUnreadPosts($userId = null)
	{
        return  parent::findThreadsWithUnreadPosts($userId = null)->where('node_id','!=',$this->app()->options()->fs_escrow_applicable_forum);
        
    }

    public function findThreadsForWatchedList($unreadOnly = false)
	{
        return  parent::findThreadsForWatchedList($unreadOnly = false)->where('node_id','!=',$this->app()->options()->fs_escrow_applicable_forum);
    }
}