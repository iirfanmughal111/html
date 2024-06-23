<?php

namespace DBTech\eCommerce\Widget;

use XF\Widget\AbstractWidget;

/**
 * Class TopProducts
 *
 * @package DBTech\eCommerce\Widget
 */
class TopProducts extends AbstractWidget
{
	/** @var array */
	protected $defaultOptions = [
		'limit' => 5,
		'style' => 'simple',
		'product_category_ids' => []
	];
	
	/**
	 * @param $context
	 *
	 * @return array
	 */
	protected function getDefaultTemplateParams($context): array
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
			/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
			$categoryRepo = $this->app->repository('DBTech\eCommerce:Category');
			$params['categoryTree'] = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
		}
		return $params;
	}
	
	/**
	 * @return string|\XF\Widget\WidgetRenderer
	 * @throws \InvalidArgumentException
	 */
	public function render()
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!method_exists($visitor, 'canViewDbtechEcommerceProducts') || !$visitor->canViewDbtechEcommerceProducts())
		{
			return '';
		}

		$options = $this->options;
		$limit = $options['limit'];
		$categoryIds = $options['product_category_ids'];

		$hasCategoryIds = ($categoryIds && !in_array(0, $categoryIds));
		$hasCategoryContext = (
			isset($this->contextParams['category'])
			&& $this->contextParams['category'] instanceof \DBTech\eCommerce\Entity\Category
		);
		$useContext = false;

		if (!$hasCategoryIds && $hasCategoryContext)
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category = $this->contextParams['category'];
			$viewableDescendents = $category->getViewableDescendants();
			$sourceCategoryIds = array_keys($viewableDescendents);
			$sourceCategoryIds[] = $category->category_id;

			$useContext = true;
		}
		elseif ($hasCategoryIds)
		{
			$sourceCategoryIds = $categoryIds;
		}
		else
		{
			$sourceCategoryIds = null;
		}

		/** @var \DBTech\eCommerce\Repository\Product $productRepo */
		$productRepo = $this->repository('DBTech\eCommerce:Product');
		
		/** @var \DBTech\eCommerce\Finder\Product $finder */
		$finder = $productRepo->findTopProducts($sourceCategoryIds);
		$finder->with('Permissions|' . $visitor->permission_combination_id);

		if (!$useContext)
		{
			// with the context, we already fetched the category and permissions
			$finder->with('Category.Permissions|' . $visitor->permission_combination_id);
		}

		if ($options['style'] == 'full')
		{
			$finder->with('full|category');
		}

		$products = $finder->fetch(max($limit * 2, 10));

		/** @var \DBTech\eCommerce\Entity\Product $product */
		foreach ($products AS $productId => $product)
		{
			if (!$product->canView() || $visitor->isIgnoring($product->user_id))
			{
				unset($products[$productId]);
			}
		}

		$total = $products->count();
		$products = $products->slice(0, $limit);

		$viewParams = [
			'title' => $this->getTitle(),
			'products' => $products,
			'style' => $options['style'],
			'hasMore' => $total > $products->count()
		];
		return $this->renderer('dbtech_ecommerce_widget_top_products', $viewParams);
	}
	
	/**
	 * @param \XF\Http\Request $request
	 * @param array $options
	 * @param null $error
	 *
	 * @return bool
	 */
	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null): bool
	{
		$options = $request->filter([
			'limit' => 'uint',
			'style' => 'str',
			'product_category_ids' => 'array-uint'
		]);
		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}
}