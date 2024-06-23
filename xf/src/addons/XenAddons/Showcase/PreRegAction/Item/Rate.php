<?php

namespace XenAddons\Showcase\PreRegAction\Item;

use XF\Entity\PreRegAction;
use XF\Entity\User;
use XF\Mvc\Entity\Entity;
use XF\PreRegAction\AbstractHandler;

class Rate extends AbstractHandler
{
	public function getContainerContentType(): string
	{
		return 'sc_item';
	}

	public function getDefaultActionData(): array
	{
		return [
			'rating' => 0,
			'title' => '',
			'pros' => '',
			'cons' => '',
			'message' => '',
			'custom_fields' => [],
			'is_anonymous' => false
		];
	}

	protected function canCompleteAction(PreRegAction $action, Entity $containerContent, User $newUser): bool
	{
		/** @var \XenAddons\Showcase\Entity\Item $containerContent */
		return $containerContent->canRate();
	}

	protected function executeAction(PreRegAction $action, Entity $containerContent, User $newUser)
	{
		/** @var \XenAddons\Showcase\Entity\Item $containerContent */

		$rater = $this->setupItemRate($action, $containerContent);
		$rater->checkForSpam();

		if (!$rater->validate())
		{
			return null;
		}

		$rating = $rater->save();

		\XF::repository('XenAddons\Showcase:ItemWatch')->autoWatchScItem($containerContent, $newUser, false);

		$rater->sendNotifications();

		return $rating;
	}

	protected function setupItemRate(
		PreRegAction $action,
		\XenAddons\Showcase\Entity\Item $item
	): \XenAddons\Showcase\Service\Item\Rate
	{
		/** @var \XenAddons\Showcase\Service\Item\Rate $rater */
		$rater = \XF::app()->service('XenAddons\Showcase:Item\Rate', $item);
		
		$rater->setRating($action->action_data['rating']);
		$rater->setTitle($action->action_data['title']);
		$rater->setPros($action->action_data['pros']);
		$rater->setCons($action->action_data['cons']);
		$rater->setMessage($action->action_data['message']);
		$rater->setCustomFields($action->action_data['custom_fields']);
		$rater->logIp($action->ip_address);
		
		if ($item->Category->allow_anon_reviews && $action->action_data['is_anonymous'])
		{
			$rater->setIsAnonymous();
		}

		return $rater;
	}

	protected function sendSuccessAlert(
		PreRegAction $action,
		Entity $containerContent,
		User $newUser,
		Entity $executeContent
	)
	{
		if (!($executeContent instanceof \XenAddons\Showcase\Entity\ItemRating))
		{
			return;
		}

		/** @var \XenAddons\Showcase\Entity\ItemRating $rating */
		$rating = $executeContent;

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = \XF::repository('XF:UserAlert');

		$alertRepo->alertFromUser(
			$newUser, null,
			'sc_rating', $rating->rating_id,
			'pre_reg'
		);
	}

	protected function getStructuredContentData(PreRegAction $preRegAction, Entity $containerContent): array
	{
		/** @var \XenAddons\Showcase\Entity\Item $containerContent */

		return [
			'title' => \XF::phrase('xa_sc_review_for_x', ['title' => $containerContent->title]),
			'title_link' => $containerContent->getContentUrl(),
			'rating' => $preRegAction->action_data['rating'],
			'text' => $preRegAction->action_data['message']
		];
	}

	protected function getApprovalQueueTemplate(): string
	{
		return 'public:pre_reg_action_approval_queue_sc_item_rate';
	}
}