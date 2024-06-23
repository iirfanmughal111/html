<?php

namespace FS\RegistrationSteps\Widget;
use XF\Widget\AbstractWidget;
class UserStates extends AbstractWidget
{
	public function render()
	{
        $ProviderUsers = $this->app->finder('XF:User')->where('account_type',2)->order('register_date','desc')->fetch(3);
        $MaleUsers = $this->app->finder('XF:User')->where('account_type',1)->order('register_date','desc')->fetch(2);
        


		/** @var \XF\Repository\SessionActivity $activityRepo */
		$activityRepo = $this->repository('XF:SessionActivity');

		$viewParams = [
			'counts' => $activityRepo->getOnlineCounts(),
			'ProviderUsers' => $ProviderUsers,
			'MaleUsers' => $MaleUsers


		];

// var_dump($activityRepo->getOnlineCounts());exit;

	
		return $this->renderer('widget_user_statistics', $viewParams);
	}

	public function getOptionsTemplate()
	{
		return null;
	}
}