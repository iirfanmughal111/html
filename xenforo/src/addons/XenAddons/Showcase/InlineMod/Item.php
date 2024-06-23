<?php

namespace XenAddons\Showcase\InlineMod;

use XF\InlineMod\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Item extends AbstractHandler
{
	public function getPossibleActions()
	{
		$actions = [];

		$actions['delete'] = $this->getActionHandler('XenAddons\Showcase:Item\Delete');

		$actions['undelete'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_undelete_items'),
			'canUndelete',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\Item $entity */
				if ($entity->item_state == 'deleted')
				{
					$entity->item_state = 'visible';
					$entity->save();
				}
			}
		);

		$actions['approve'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_approve_items'),
			'canApproveUnapprove',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\Item $entity */
				if ($entity->item_state == 'moderated')
				{
					/** @var \XenAddons\Showcase\Service\Item\Approve $approver */
					$approver = \XF::service('XenAddons\Showcase:Item\Approve', $entity);
					$approver->setNotifyRunTime(1); // may be a lot happening
					$approver->approve();
				}
			}
		);

		$actions['unapprove'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_unapprove_items'),
			'canApproveUnapprove',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\Item $entity */
				if ($entity->item_state == 'visible')
				{
					$entity->item_state = 'moderated';
					$entity->save();
				}
			}
		);

		$actions['feature'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_feature_items'),
			'canFeatureUnfeature',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Service\Item\Feature $featurer */
				$featurer = $this->app->service('XenAddons\Showcase:Item\Feature', $entity);
				$featurer->feature();
			}
		);

		$actions['unfeature'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_unfeature_items'),
			'canFeatureUnfeature',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Service\Item\Feature $featurer */
				$featurer = $this->app->service('XenAddons\Showcase:Item\Feature', $entity);
				$featurer->unfeature();
			}
		);
		
		$actions['stick'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_stick_items'),
			'canStickUnstick',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\Item $entity */
				$entity->sticky = true;
				$entity->save();
			}
		);
		
		$actions['unstick'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_unstick_items'),
			'canStickUnstick',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\Item $entity */
				$entity->sticky = false;
				$entity->save();
			}
		);

		$actions['reassign'] = $this->getActionHandler('XenAddons\Showcase:Item\Reassign');
		$actions['move'] = $this->getActionHandler('XenAddons\Showcase:Item\Move');
		$actions['merge'] = $this->getActionHandler('XenAddons\Showcase:Item\Merge');
		$actions['apply_prefix'] = $this->getActionHandler('XenAddons\Showcase:Item\ApplyPrefix');

		return $actions;
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id];
	}
}