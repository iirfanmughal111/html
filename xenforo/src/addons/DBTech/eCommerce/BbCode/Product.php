<?php

namespace DBTech\eCommerce\BbCode;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\BbCode
 */
class Product
{
	/**
	 * @param array $tagChildren
	 * @param $tagOption
	 * @param array $tag
	 * @param array $options
	 * @param \XF\BbCode\Renderer\AbstractRenderer $renderer
	 *
	 * @return string
	 */
	public static function renderTagProduct(
		array $tagChildren,
		$tagOption,
		array $tag,
		array $options,
		\XF\BbCode\Renderer\AbstractRenderer $renderer
	): string {
		if (!$tag['option'])
		{
			return $renderer->renderUnparsedTag($tag, $options);
		}

		$parts = explode(',', $tag['option']);
		foreach ($parts AS &$part)
		{
			$part = trim($part);
			$part = str_replace(' ', '', $part);
		}

		$type = $renderer->filterString(
			array_shift($parts),
			array_merge($options, [
				'stopSmilies' => true,
				'stopLineBreakConversion' => true
			])
		);
		$type = strtolower($type);
		$id = array_shift($parts);

		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();

		if (!$visitor->canViewDbtechEcommerceProducts()
			|| $renderer instanceof \XF\BbCode\Renderer\SimpleHtml
			|| $renderer instanceof \XF\BbCode\Renderer\EmailHtml
		) {
			return self::renderTagSimple($type, $id);
		}

		$viewParams = [
			'type' => $type,
			'id' => intval($id),
			'text' => isset($tag['children']) ? $tag['children'] : ''
		];

		if ($type == 'product')
		{
			if (isset($options['dbtechEcommerceProducts'][$id]))
			{
				$product = $options['dbtechEcommerceProducts'][$id];
			}
			else
			{
				/** @var \DBTech\eCommerce\Entity\Product $product */
				$product = \XF::em()->find('DBTech\eCommerce:Product', $id, [
					'Permissions|' . $visitor->permission_combination_id,
					'Category.Permissions|' . $visitor->permission_combination_id
				]);
			}
			if (!$product || !$product->canView())
			{
				return self::renderTagSimple($type, $id);
			}
			elseif ($visitor->isIgnoring($product->user_id))
			{
				return '';
			}
			
			if (!$product || !$product->canView())
			{
				return self::renderTagSimple($type, $id);
			}
			elseif ($visitor->isIgnoring($product->user_id))
			{
				return '';
			}
			$viewParams['product'] = $product;
			
			return $renderer->getTemplater()->renderTemplate('public:dbtech_ecommerce_product_bb_code_product', $viewParams);
		}

		return self::renderTagSimple($type, $id);
	}
	
	/**
	 * @param string $type
	 * @param int $id
	 *
	 * @return string
	 */
	protected static function renderTagSimple(string $type, int $id): string
	{
		$router = \XF::app()->router('public');

		switch ($type)
		{
			case 'product':

				$link = $router->buildLink('full:dbtech-ecommerce', ['product_id' => $id]);
				$phrase = \XF::phrase('dbtech_ecommerce_view_product_x', ['id' => $id]);

				return '<a href="' . htmlspecialchars($link) .'">' . $phrase .'</a>';

			default:

				return '[PRODUCT]';
		}
	}
}