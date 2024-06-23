<?php

namespace XenAddons\Showcase\InlineMod\ItemUpdate;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

class Delete extends AbstractAction
{
	public function getTitle()
	{
		return \XF::phrase('xa_sc_delete_updates...');
	}

	protected function canApplyToEntity(Entity $entity, array $options, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemUpdate $entity */
		return $entity->canDelete($options['type'], $error);
	}

	protected function applyToEntity(Entity $entity, array $options)
	{
		/** @var \XenAddons\Showcase\Service\ItemUpdate\Deleter $deleter */
		$deleter = $this->app()->service('XenAddons\Showcase:ItemUpdate\Deleter', $entity);

		if ($options['alert'])
		{
			$deleter->setSendAlert(true, $options['alert_reason']);
		}

		$deleter->delete($options['type'], $options['reason']);
	}

	public function getBaseOptions()
	{
		return [
			'type' => 'soft',
			'reason' => '',
			'alert' => false,
			'alert_reason' => ''
		];
	}

	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
	{
		$viewParams = [
			'updates' => $entities,
			'total' => count($entities),
			'canHardDelete' => $this->canApply($entities, ['type' => 'hard'])
		];
		return $controller->view('XenAddons\Showcase:Public:InlineMod\ItemUpdate\Delete', 'xa_sc_inline_mod_update_delete', $viewParams);
	}

	public function getFormOptions(AbstractCollection $entities, Request $request)
	{
		return [
			'type' => $request->filter('hard_delete', 'bool') ? 'hard' : 'soft',
			'reason' => $request->filter('reason', 'str'),
			'alert' => $request->filter('author_alert', 'bool'),
			'alert_reason' => $request->filter('author_alert_reason', 'str')
		];
	}
}