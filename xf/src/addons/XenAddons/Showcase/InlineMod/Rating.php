<?php

namespace XenAddons\Showcase\InlineMod;

use XF\InlineMod\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Rating extends AbstractHandler
{
	public function getPossibleActions()
	{
		$actions = [];

		$actions['delete'] = $this->getActionHandler('XenAddons\Showcase:Rating\Delete');

		$actions['undelete'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_undelete_reviews'),
			'canUndelete',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\ItemRating $entity */
				if ($entity->rating_state == 'deleted')
				{
					$entity->rating_state = 'visible';
					$entity->save();
				}
			}
		);

		$actions['approve'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_approve_reviews'),
			'canApproveUnapprove',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\ItemRating $entity */
				if ($entity->rating_state == 'moderated')
				{
					$entity->rating_state = 'visible';
					$entity->save();
				}
			}
		);

		$actions['unapprove'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_unapprove_reviews'),
			'canApproveUnapprove',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\ItemRating $entity */
				if ($entity->rating_state == 'visible')
				{
					$entity->rating_state = 'moderated';
					$entity->save();
				}
			}
		);
		
		$actions['move'] = $this->getActionHandler('XenAddons\Showcase:Rating\Move');

		return $actions;
	}

	public function getEntityWith()
	{
		return 'User';
	}
}