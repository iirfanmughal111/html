<?php

namespace XenAddons\Showcase\Poll;

use XF\Entity\Poll;
use XF\Mvc\Entity\Entity;
use XF\Poll\AbstractHandler;

class ItemPage extends AbstractHandler
{
	public function canCreate(Entity $content, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $content */

		return $content->canCreatePoll($error);
	}

	public function canEdit(Entity $content, Poll $poll, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $content */

		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		$categoryId = $content->Item->category_id;

		if ($visitor->hasShowcaseItemCategoryPermission($categoryId, 'editAny'))
		{
			return true;
		}

		if ($content->Item->isContributor() && $visitor->hasShowcaseItemCategoryPermission($categoryId, 'editOwn'))
		{
			$editLimit = $visitor->hasShowcaseItemCategoryPermission($categoryId, 'editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $content->create_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phraseDeferred('message_edit_time_limit_expired', ['minutes' => $editLimit]);
				return false;
			}

			return true;
		}

		return false;
	}

	public function canAlwaysEditDetails(Entity $content, Poll $poll, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $content */

		$visitor = \XF::visitor();
		return ($visitor->user_id && $visitor->hasShowcaseItemCategoryPermission($content->Item->category_id, 'editAny'));
	}

	public function canDelete(Entity $content, Poll $poll, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $content */

		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($visitor->hasShowcaseItemCategoryPermission($content->Item->category_id, 'editAny'))
		{
			return true;
		}

		if ($visitor->user_id != $content->user_id)
		{
			return false;
		}

		return ($poll->voter_count == 0);
	}

	public function canVote(Entity $content, Poll $poll, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $content */

		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		return $visitor->hasShowcaseItemCategoryPermission($content->Item->category_id, 'votePoll');
	}

	public function getPollLink($action, Entity $content, array $extraParams = [])
	{
		if ($action == 'content')
		{
			return \XF::app()->router('public')->buildLink('showcase/page', $content, $extraParams);
		}
		else
		{
			return \XF::app()->router('public')->buildLink('showcase/page/poll/' . $action, $content, $extraParams);
		}
	}

	public function finalizeCreation(Entity $content, Poll $poll)
	{
		$content->has_poll = true;
		$content->save();		
	}

	public function finalizeDeletion(Entity $content, Poll $poll)
	{
		$content->has_poll = false;
		$content->save();
	}

	public function getEntityWith()
	{
		return ['Page', 'Page.Item', 'User'];
	}
}