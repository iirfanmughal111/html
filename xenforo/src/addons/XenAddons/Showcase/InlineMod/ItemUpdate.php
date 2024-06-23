<?php

namespace XenAddons\Showcase\InlineMod;

use XF\InlineMod\AbstractHandler;
use XF\Mvc\Entity\Entity;

class ItemUpdate extends AbstractHandler
{
	public function getPossibleActions()
	{
		$actions = [];

		$actions['delete'] = $this->getActionHandler('XenAddons\Showcase:ItemUpdate\Delete');

		$actions['undelete'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_undelete_updates'),
			'canUndelete',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\ItemUpdate $entity */
				if ($entity->update_state == 'deleted')
				{
					$entity->update_state = 'visible';
					$entity->save();
				}
			}
		);

		$actions['approve'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_approve_updates'),
			'canApproveUnapprove',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\ItemUpdate $entity */
				if ($entity->update_state == 'moderated')
				{
					$entity->update_state = 'visible';
					$entity->save();
				}
			}
		);

		$actions['unapprove'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_unapprove_updates'),
			'canApproveUnapprove',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\ItemUpdate $entity */
				if ($entity->update_state == 'visible')
				{
					$entity->update_state = 'moderated';
					$entity->save();
				}
			}
		);

		return $actions;
	}

	public function getEntityWith()
	{
		return 'User';
	}
}