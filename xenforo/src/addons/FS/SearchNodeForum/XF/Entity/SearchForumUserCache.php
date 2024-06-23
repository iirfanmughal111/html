<?php

namespace FS\SearchNodeForum\XF\Entity;

use XF\Mvc\ParameterBag;

class SearchForumUserCache extends XFCP_SearchForumUserCache{
    
    // public function getThreadsByPage(int $page, int $perPage, array $extraWith = [])
	// {
		
    //     $inputs = \XF::app()->request->filter([
    //         'prefix_id' => 'uint',
    //         'starter_id' => 'int',
    //         'starter' => 'str',
    //         'last_days' => 'int',
    //         'order' => 'str',
    //         'direction' => 'str',
    //         'apply' => 'bool',
    //         'thread_type' => 'str'
    //     ]);
        
        
    //     if (count(array_filter($inputs)))
    //     {
	// 		$threadIds = $this->results;
	// 	} else {
	// 		$threadIds = $this->sliceResultsToPage($page, $perPage);
	// 	}
   
	// 	return $this->getSearchForumRepo()
	// 		->getThreadsByIdsOrdered($threadIds, $extraWith)
	// 		->filterViewable();
	// }
}