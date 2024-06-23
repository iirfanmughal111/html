<?php

namespace Banxix\BumpThread\XF\Admin\Controller;

class Permission extends XFCP_Permission
{
	public function actionAnalyze()
	{
		$view = parent::actionAnalyze();

		if ($view instanceof \XF\Mvc\Reply\Error)
		{
			return $view;
		}

		if ($analysis = $view->getParam('analysis'))
		{
			if (!isset($analysis['forum']))
			{
				return $view;
			}

			$intermediates = $analysis['forum']['bumpFloodRate']['intermediates'];

			$permissionsGroup = $permissionsContent = [];
			foreach ($intermediates as $intermediate)
			{
				if ($intermediate->value !== 0)
				{
					if (empty($intermediate->contentId))
					{
						$permissionsGroup[] = $intermediate->value;
					}
					else
					{
						$permissionsContent[] = $intermediate->value;
					}
				}
			}

			$analysis['forum']['bumpFloodRate']['final'] = empty($permissionsContent)
				? (empty($permissionsGroup) ? 0 : min($permissionsGroup))
				: min($permissionsContent);

			$view->setParam('analysis', $analysis);
		}

		return $view;
	}
}