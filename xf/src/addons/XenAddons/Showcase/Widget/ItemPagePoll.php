<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractPollWidget;

class ItemPagePoll extends AbstractPollWidget
{
	public function getPollFromRoutePath($routePath, &$error = null)
	{
		$itemPage = $this->repository('XenAddons\Showcase:ItemPage')->getItemPageFromUrl($routePath, 'public', $error);
		if (!$itemPage)
		{
			return false;
		}

		if (!$itemPage->Poll)
		{
			$error = \XF::phrase('xa_sc_specified_item_page_does_not_have_poll_attached_to_it');
			return false;
		}

		return $itemPage->Poll;
	}

	public function getDefaultTitle()
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $content */
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
		/** @var \XenAddons\Showcase\Entity\ItemPage $content */
		$content = $this->getContent();
		if ($content && $content->canView() && $content->Poll)
		{
			$viewParams = [
				'content' => $content,
				'poll' => $content->Poll
			];
			return $this->renderer('xa_sc_widget_item_page_poll', $viewParams);
		}

		return '';
	}

	public function getEntityWith()
	{
		return [
			'Poll',
			'Item',
			'Item.Category',
			'Item.Category.Permissions|' . \XF::visitor()->permission_combination_id
		];
	}
}