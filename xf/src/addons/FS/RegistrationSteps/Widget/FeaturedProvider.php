<?php

namespace FS\RegistrationSteps\Widget;
use XF\Widget\AbstractWidget;
class FeaturedProvider extends AbstractWidget
{
	public function render()
	{
        $users = $this->app->finder('XF:User')->where('is_featured',1)->fetch(20);

		$viewParams = [
			'users' => $users
		];
	
		return $this->renderer('widget_featured_provider', $viewParams);
	}

	public function getOptionsTemplate()
	{
		return null;
	}
}