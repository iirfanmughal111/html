<?php

namespace XenAddons\Showcase\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;
use XenAddons\Showcase\Entity\SeriesItem;

class SeriesIcon extends AbstractPlugin
{
	public function actionUpload(SeriesItem $series)
	{
		/** @var \XenAddons\Showcase\Service\Series\Icon $iconService */
		$iconService = $this->service('XenAddons\Showcase:Series\Icon', $series);

		$action = $this->filter('icon_action', 'str');

		if ($action == 'delete')
		{
			$iconService->deleteIcon();
		}
		else if ($action == 'custom')
		{
			$upload = $this->request->getFile('upload', false, false);
			if ($upload)
			{
				if (!$iconService->setImageFromUpload($upload))
				{
					throw $this->exception($this->error($iconService->getError()));
				}

				if (!$iconService->updateIcon())
				{
					throw $this->exception($this->error(\XF::phrase('xa_sc_new_icon_could_not_be_applied_try_later')));
				}
			}
		}
	}
}