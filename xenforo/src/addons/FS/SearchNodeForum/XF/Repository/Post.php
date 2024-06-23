<?php

namespace FS\SearchNodeForum\XF\Repository;

use XF\Mvc\ParameterBag;

use XF\Mvc\Entity\Repository;

class Post extends Repository
{
    public function getPostByApplyFilter(array $postIds){
        
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

		$finder = $this->finder('XF:Post')->whereIds($postIds);
        if (count(array_filter($inputs))|| \XF::app()->request->filter('apply','bool'))
		{
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
					$finder->where('post_date', '>',$days);
				}
			


    	}
        return $finder;

    }
    
   
}