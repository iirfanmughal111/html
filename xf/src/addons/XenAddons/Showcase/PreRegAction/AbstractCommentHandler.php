<?php

namespace XenAddons\Showcase\PreRegAction;

use XF\Entity\PreRegAction;
use XF\Entity\User;
use XF\Mvc\Entity\Entity;

abstract class AbstractCommentHandler extends \XF\PreRegAction\AbstractHandler
{
	public function getDefaultActionData(): array
	{
		return [
			'message' => ''
		];
	}

	protected function canCompleteAction(PreRegAction $action, Entity $containerContent, User $newUser): bool
	{
		/** @var \XenAddons\Showcase\Entity\Item $containerContent */
		return $containerContent->canAddComment();
	}

	protected function executeAction(PreRegAction $action, Entity $containerContent, User $newUser)
	{
		/** @var \XenAddons\Showcase\Entity\Item $containerContent */

		$creator = $this->setupCommentCreate($action, $containerContent);
		$creator->checkForSpam();

		if (!$creator->validate())
		{
			return null;
		}

		$comment = $creator->save();

		\XF::repository('XenAddons\Showcase:ItemWatch')->autoWatchScItem($containerContent, $newUser, false);

		$creator->sendNotifications();

		return $comment;
	}

	protected function setupCommentCreate(
		PreRegAction $action,
		\XF\Mvc\Entity\Entity $containerContent
	): \XenAddons\Showcase\Service\Comment\Creator
	{
		$creator = \XF::app()->service('XenAddons\Showcase:Comment\Creator', $containerContent);
		$creator->setMessage($action->action_data['message']);
		$creator->logIp($action->ip_address);

		return $creator;
	}

	protected function sendSuccessAlert(
		PreRegAction $action,
		Entity $containerContent,
		User $newUser,
		Entity $executeContent
	)
	{
		if (!($executeContent instanceof \XenAddons\Showcase\Entity\Comment))
		{
			return;
		}

		/** @var \XenAddons\Showcase\Entity\Comment $comment */
		$comment = $executeContent;

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = \XF::repository('XF:UserAlert');

		$alertRepo->alertFromUser(
			$newUser, null,
			'sc_comment', $comment->comment_id,
			'pre_reg',
			[],
			['autoRead' => false]
		);
	}

	protected function getStructuredContentData(PreRegAction $preRegAction, Entity $containerContent): array
	{
		/** @var \XenAddons\Showcase\Entity\Item $containerContent */

		$phrase = 'xa_sc_comment_on_item_x';

		return [
			'title' => \XF::phrase($phrase, [
				'title' => $containerContent->title
			]),
			'title_link' => $containerContent->getContentUrl(),
			'bb_code' => $preRegAction->action_data['message']
		];
	}
}