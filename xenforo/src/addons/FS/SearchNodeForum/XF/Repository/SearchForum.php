<?php

namespace FS\SearchNodeForum\XF\Repository;

use XF\Mvc\ParameterBag;

class SearchForum extends XFCP_SearchForum
{
    public function getThreadByApplyFilter(array $threadIds){
        
    		$inputs = \XF::app()->request->filter([
				'prefix_id' => 'uint',
				'starter_id' => 'int',
				'starter' => 'str',
				'last_days' => 'int',
				'order' => 'str',
				'direction' => 'str',
				'apply' => 'bool',
				'thread_type' => 'str'
			]);

		$finder = $this->finder('XF:Thread')->whereIds($threadIds);
        if (count(array_filter($inputs))|| \XF::app()->request->filter('apply','bool'))
		{
            
		if ($inputs['prefix_id'] && $inputs['prefix_id'] != 0){
			
			$finder->where('prefix_id', $inputs['prefix_id']);
		}
		if ($inputs['starter'] ){
			$user =  \XF::app()->em()->findOne('XF:User', ['username' => $inputs['starter']]);
				if ($user)
				{
					$inputs['starter_id'] = $user->user_id;
				}
	
			$finder->where('user_id', intval($inputs['starter_id']));

		}

        if ($inputs['starter_id'] ){
			
			$finder->where('user_id', intval($inputs['starter_id']));
		}

		if ($inputs['last_days'] && $inputs['last_days'] > 0){
			$days = (time() - (86400*$inputs['last_days']));
			$finder->where('last_post_date', '>',$days);
		}
	
		if ($inputs['thread_type'] ){
			
			$finder->where('discussion_type', $inputs['thread_type']);
		}

		if (!empty($inputs['order'])){
		
			$finder->order($inputs['order'], $inputs['direction']);
		}
    }
		$this->setupFinderForSearchForum($finder);
        return $finder;

    }
    
   
}