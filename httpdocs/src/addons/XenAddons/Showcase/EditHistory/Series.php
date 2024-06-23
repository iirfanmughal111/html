<?php

namespace XenAddons\Showcase\EditHistory;

use XF\EditHistory\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Series extends AbstractHandler
{
	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $content
	 */
	public function canViewHistory(Entity $content)
	{
		return ($content->canView() && $content->canViewHistory());
	}

	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $content
	 */
	public function canRevertContent(Entity $content)
	{
		return $content->canEdit();
	}

	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $content
	 */
	public function getContentTitle(Entity $content)
	{
		return $content->title;
	}

	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $content
	 */
	public function getContentText(Entity $content)
	{
		return $content->message;
	}

	public function getContentLink(Entity $content)
	{
		return \XF::app()->router()->buildLink('showcase/series/details', $content);
	}

	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $content
	 */
	public function getBreadcrumbs(Entity $content)
	{
		return $content->getBreadcrumbs();
	}

	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $content
	 */
	public function revertToVersion(Entity $content, \XF\Entity\EditHistory $history, \XF\Entity\EditHistory $previous = null)
	{
		/** @var \XenAddons\Showcase\Service\Series\Edit $editor */
		$editor = \XF::app()->service('XenAddons\Showcase:Series\Edit', $content);

		$editor->logEdit(false);
		$editor->setMessage($history->old_text);

		if (!$previous || $previous->edit_user_id != $content->user_id)
		{
			$content->last_edit_date = 0;
		}
		else if ($previous && $previous->edit_user_id == $content->user_id)
		{
			$content->last_edit_date = $previous->edit_date;
			$content->last_edit_user_id = $previous->edit_user_id;
		}

		return $editor->save();
	}

	public function getHtmlFormattedContent($text, Entity $content = null)
	{
		return \XF::app()->templater()->func('bb_code', [$text, 'sc_series', $content]);
	}

	public function getSectionContext()
	{
		return 'xa_showcase';
	}
}