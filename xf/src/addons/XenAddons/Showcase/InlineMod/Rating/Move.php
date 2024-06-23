<?php

namespace XenAddons\Showcase\InlineMod\Rating;

use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

class Move extends AbstractMoveCopy
{
	public function getTitle()
	{
		return \XF::phrase('xa_sc_move_reviews...');
	}

	protected function canApplyToEntity(Entity $entity, array $options, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemRating $entity */
		return $entity->canMove($error);
	}

	public function applyInternal(AbstractCollection $entities, array $options)
	{
		$item = $this->getTargetItemFromOptions($options);

		/** @var \XenAddons\Showcase\Service\Review\Mover $mover */
		$mover = $this->app()->service('XenAddons\Showcase:Review\Mover', $item);
		$mover->setExistingTarget($options['item_type'] == 'existing' ? true : false);

		if ($options['alert'])
		{
			$mover->setSendAlert(true, $options['alert_reason']);
		}

		if (!$mover->move($entities))
		{
			throw new \XF\PrintableException(\XF::phrase('xa_sc_it_is_not_possible_to_move_any_of_selected_reviews_to_specified_target'));
		}

		$this->returnUrl = $this->app()->router('public')->buildLink('showcase', $mover->getTarget());
	}

	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
	{
		$viewParams = [
			'reviews' => $entities,
			'total' => count($entities),
			'first' => $entities->first(),
		];
		return $controller->view('XenAddons\Showcase:Public:InlineMod\Review\Move', 'xa_sc_inline_mod_review_move', $viewParams);
	}
}