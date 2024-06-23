<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractPollWidget;

class ItemPoll extends AbstractPollWidget
{
	public function getPollFromRoutePath($routePath, &$error = null)
	{
		$item = $this->repository('XenAddons\Showcase:Item')->getItemFromUrl($routePath, 'public', $error);
		if (!$item)
		{
			return false;
		}

		if (!$item->Poll)
		{
			$error = \XF::phrase('xa_sc_specified_item_does_not_have_poll_attached_to_it');
			return false;
		}

		return $item->Poll;
	}

	public function getDefaultTitle()
	{
		/** @var \XenAddons\Showcase\Entity\Item $content */
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
		/** @var \XenAddons\Showcase\Entity\Item $content */
		$content = $this->getContent();
		if ($content && $content->canView() && $content->Poll)
		{
			$viewParams = [
				'content' => $content,
				'poll' => $content->Poll
			];
			return $this->renderer('xa_sc_widget_item_poll', $viewParams);
		}

		return '';
	}

	public function getEntityWith()
	{
		return [
			'Poll',
			'Category',
			'Category.Permissions|' . \XF::visitor()->permission_combination_id
		];
	}
}