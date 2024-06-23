<?php

namespace XenAddons\Showcase\InlineMod;

use XF\InlineMod\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Series extends AbstractHandler
{
	public function getPossibleActions()
	{
		$actions = [];

		$actions['delete'] = $this->getActionHandler('XenAddons\Showcase:Series\Delete');

		$actions['undelete'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_undelete_series'),
			'canUndelete',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\Series $entity */
				if ($entity->series_state == 'deleted')
				{
					$entity->series_state = 'visible';
					$entity->save();
				}
			}
		);

		$actions['approve'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_approve_series'),
			'canApproveUnapprove',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\Series $entity */
				if ($entity->series_state == 'moderated')
				{
					/** @var \XenAddons\Showcase\Service\Series\Approve $approver */
					$approver = \XF::service('XenAddons\Showcase:Series\Approve', $entity);
					$approver->setNotifyRunTime(1); // may be a lot happening
					$approver->approve();
				}
			}
		);

		$actions['unapprove'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_unapprove_series'),
			'canApproveUnapprove',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Entity\Series $entity */
				if ($entity->series_state == 'visible')
				{
					$entity->series_state = 'moderated';
					$entity->save();
				}
			}
		);

		$actions['feature'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_feature_series'),
			'canFeatureUnfeature',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Service\Series\Feature $featurer */
				$featurer = $this->app->service('XenAddons\Showcase:Series\Feature', $entity);
				$featurer->feature();
			}
		);

		$actions['unfeature'] = $this->getSimpleActionHandler(
			\XF::phrase('xa_sc_unfeature_series'),
			'canFeatureUnfeature',
			function(Entity $entity)
			{
				/** @var \XenAddons\Showcase\Service\Series\Feature $featurer */
				$featurer = $this->app->service('XenAddons\Showcase:Series\Feature', $entity);
				$featurer->unfeature();
			}
		);

		$actions['reassign'] = $this->getActionHandler('XenAddons\Showcase:Series\Reassign');

		return $actions;
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return [];
	}
}