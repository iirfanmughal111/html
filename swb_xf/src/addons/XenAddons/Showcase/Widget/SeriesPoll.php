<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractPollWidget;

class SeriesPoll extends AbstractPollWidget
{
	public function getPollFromRoutePath($routePath, &$error = null)
	{
		$series = $this->repository('XenAddons\Showcase:Series')->getSeriesFromUrl($routePath, 'public', $error);
		if (!$series)
		{
			return false;
		}

		if (!$series->Poll)
		{
			$error = \XF::phrase('xa_sc_specified_series_does_not_have_poll_attached_to_it');
			return false;
		}

		return $series->Poll;
	}

	public function getDefaultTitle()
	{
		/** @var \XenAddons\Showcase\Entity\SeriesItem $content */
		$content = $this->getContent();
		if ($content && $content->canView() && $content->Poll)
		{
			return $content->Poll->question;
		}
		else
		{
			return parent::getDefaultTitle();
		}
	}

	public function render()
	{
		/** @var \XenAddons\Showcase\Entity\SeriesItem $content */
		$content = $this->getContent();
		if ($content && $content->canView() && $content->Poll)
		{
			$viewParams = [
				'content' => $content,
				'poll' => $content->Poll
			];
			return $this->renderer('xa_sc_widget_series_poll', $viewParams);
		}

		return '';
	}

	public function getEntityWith()
	{
		return ['Poll'];
	}
}