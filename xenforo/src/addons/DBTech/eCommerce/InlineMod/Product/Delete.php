<?php

namespace DBTech\eCommerce\InlineMod\Product;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\InlineMod\Product
 */
class Delete extends AbstractAction
{
	/**
	 * @return \XF\Phrase
	 */
	public function getTitle(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_delete_products...');
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 * @param null $error
	 *
	 * @return bool|mixed
	 */
	protected function canApplyToEntity(Entity $entity, array $options, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\Entity\Product $entity */
		return $entity->canDelete($options['type'], $error);
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 *
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function applyToEntity(Entity $entity, array $options)
	{
		/** @var \DBTech\eCommerce\Service\Product\Delete $deleter */
		$deleter = $this->app()->service('DBTech\eCommerce:Product\Delete', $entity);

		if ($options['alert'])
		{
			$deleter->setSendAlert(true, $options['alert_reason']);
		}

		$deleter->delete($options['type'], $options['reason']);

		if ($options['type'] == 'hard')
		{
			$this->returnUrl = $this->app()->router()->buildLink('dbtech-ecommerce/categories', $entity->Category);
		}
	}
	
	/**
	 * @return array
	 */
	public function getBaseOptions(): array
	{
		return [
			'type' => 'soft',
			'reason' => '',
			'alert' => false,
			'alert_reason' => ''
		];
	}

	/**
	 * @param \XF\Mvc\Entity\AbstractCollection $entities
	 * @param \XF\Mvc\Controller $controller
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 */
	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'products' => $entities,
			'total' => count($entities),
			'canHardDelete' => $this->canApply($entities, ['type' => 'hard'])
		];
		return $controller->view('DBTech\eCommerce:Public:InlineMod\Product\Delete', 'inline_mod_dbtech_ecommerce_product_delete', $viewParams);
	}
	
	/**
	 * @param AbstractCollection $entities
	 * @param Request $request
	 *
	 * @return array
	 */
	public function getFormOptions(AbstractCollection $entities, Request $request): array
	{
		return [
			'type' => $request->filter('hard_delete', 'bool') ? 'hard' : 'soft',
			'reason' => $request->filter('reason', 'str'),
			'alert' => $request->filter('author_alert', 'bool'),
			'alert_reason' => $request->filter('author_alert_reason', 'str')
		];
	}
}