<?php

namespace FS\RegistrationSteps\Widget;
use XF\Widget\AbstractWidget;
class TopUsers extends AbstractWidget
{
	public function render()
	{
        $users_id = $this->app->finder('XF:Thread')->where('review_for','!=',0)->fetch(5);


        $query = "SELECT user_id,username,COUNT(*) as total
                    FROM `xf_thread` 
                    where review_for != 0
                 
                    GROUP by user_id , username
                    ORDER by total DESC
                   
                    LIMIT 5";

            $users  = \XF::db()->fetchAll($query);
        
		$viewParams = [
			'users' => $users
		];
	
		return $this->renderer('widget_top_users', $viewParams);
	}

	public function getOptionsTemplate()
	{
		return null;
	}
}