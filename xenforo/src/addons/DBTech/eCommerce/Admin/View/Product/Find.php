<?php

namespace DBTech\eCommerce\Admin\View\Product;

use XF\Mvc\View;

/**
 * Class Find
 *
 * @package DBTech\eCommerce\Admin\View\Product
 */
class Find extends View
{
	/**
	 * @return array
	 */
	public function renderJson(): array
	{
		$templater = $this->renderer->getTemplater();
		$func = \XF::$versionId >= 2010370 ? 'func' : 'fn';
		
		$results = [];
		foreach ($this->params['products'] AS $product)
		{
			$iconArgs = [$product, 'xxs', false, ['href' => '']];

			$results[] = [
				'id' => $product->title,
				'iconHtml' => $templater->$func('dbtech_ecommerce_product_icon', $iconArgs),
				'text' => $product->title,
				'q' => $this->params['q']
			];
		}

		return [
			'results' => $results,
			'q' => $this->params['q']
		];
	}
}