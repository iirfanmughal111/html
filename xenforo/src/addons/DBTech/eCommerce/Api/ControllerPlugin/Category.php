<?php

namespace DBTech\eCommerce\Api\ControllerPlugin;

use XF\Api\ControllerPlugin\AbstractPlugin;

/**
 * Class Category
 *
 * @package DBTech\eCommerce\Api\ControllerPlugin
 */
class Category extends AbstractPlugin
{
	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 *
	 * @return \XF\Mvc\FormAction
	 *
	 * @api-in str $category[title]
	 * @api-in str $category[description]
	 * @api-in int $category[parent_node_id]
	 * @api-in int $category[display_order]
	 * @api-int bool $category[always_moderate_create]
	 * @api-int bool $category[always_moderate_update]
	 * @api-int int $category[thread_node_id]
	 * @api-int int $category[thread_prefix_id]
	 * @api-int bool $category[require_prefix]
	 * @api-int int $category[min_tags]
	 * @api-int str $category[product_update_notify]
	 */
	public function setupCategorySave(\DBTech\eCommerce\Entity\Category $category): \XF\Mvc\FormAction
	{
		$entityInput = $this->filter([
			'title' => '?str',
			'description' => '?str',
			'parent_category_id' => '?uint',
			'display_order' => '?uint',
			'always_moderate_create' => '?bool',
			'always_moderate_update' => '?bool',
			'thread_node_id' => '?uint',
			'thread_prefix_id' => '?uint',
			'require_prefix' => '?bool',
			'min_tags' => '?uint',
			'product_update_notify' => '?str'
		]);
		$entityInput = \XF\Util\Arr::filterNull($entityInput);

		$form = $this->formAction();
		$form->basicEntitySave($category, $entityInput);

		return $form;
	}
}