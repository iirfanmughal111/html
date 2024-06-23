<?php

namespace DBTech\eCommerce\FindNew;

use XF\Entity\FindNew;
use XF\FindNew\AbstractHandler;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\FindNew
 */
class Product extends AbstractHandler
{
	/**
	 * @return string
	 */
	public function getRoute(): string
	{
		return 'whats-new/ecommerce-products';
	}
	
	/**
	 * @param \XF\Mvc\Controller $controller
	 * @param FindNew $findNew
	 * @param array $results
	 * @param $page
	 * @param $perPage
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	public function getPageReply(
		\XF\Mvc\Controller $controller,
		FindNew $findNew,
		array $results,
		$page,
		$perPage
	): \XF\Mvc\Reply\AbstractReply {
		$canInlineMod = false;

		/** @var \DBTech\eCommerce\Entity\Product $product */
		foreach ($results AS $product)
		{
			if ($product->canUseInlineModeration())
			{
				$canInlineMod = true;
				break;
			}
		}

		$viewParams = [
			'findNew' => $findNew,

			'page' => $page,
			'perPage' => $perPage,

			'products' => $results,
			'canInlineMod' => $canInlineMod
		];
		return $controller->view('DBTech\eCommerce:WhatsNew\Products', 'dbtech_ecommerce_whats_new_products', $viewParams);
	}
	
	/**
	 * @param \XF\Http\Request $request
	 *
	 * @return array
	 */
	public function getFiltersFromInput(\XF\Http\Request $request): array
	{
		$filters = [];

		$visitor = \XF::visitor();

		$watched = $request->filter('watched', 'bool');
		if ($watched && $visitor->user_id)
		{
			$filters['watched'] = true;
		}

		return $filters;
	}
	
	/**
	 * @return array
	 */
	public function getDefaultFilters(): array
	{
		return [];
	}
	
	/**
	 * @param array $filters
	 * @param $maxResults
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getResultIds(array $filters, $maxResults): array
	{
		$visitor = \XF::visitor();

		/** @var \DBTech\eCommerce\Finder\Product $finder */
		$finder = \XF::finder('DBTech\eCommerce:Product')
			->with('Permissions|' . $visitor->permission_combination_id)
			->with('Category', true)
			->with('Category.Permissions|' . $visitor->permission_combination_id)
			->where('parent_product_id', 0)
			->where('is_listed', true)
			->where('product_state', '<>', 'deleted')
			->where('last_update', '>', \XF::$time - (86400 * \XF::options()->readMarkingDataLifetime))
			->order('last_update', 'DESC');

		$this->applyFilters($finder, $filters);

		$products = $finder->fetch($maxResults);
		$products = $this->filterResults($products);

		// TODO: consider overfetching or some other permission limits within the query

		return $products->keys();
	}
	
	/**
	 * @param array $ids
	 *
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	public function getPageResultsEntities(array $ids): \XF\Mvc\Entity\AbstractCollection
	{
		$visitor = \XF::visitor();

		$ids = array_map('intval', $ids);

		/** @var \DBTech\eCommerce\Finder\Product $finder */
		$finder = \XF::finder('DBTech\eCommerce:Product')
			->where('product_id', $ids)
			->with('full|category')
		;

		return $finder->fetch();
	}
	
	/**
	 * @param \XF\Mvc\Entity\AbstractCollection $results
	 *
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	protected function filterResults(\XF\Mvc\Entity\AbstractCollection $results): \XF\Mvc\Entity\AbstractCollection
	{
		$visitor = \XF::visitor();

		return $results->filter(function (\DBTech\eCommerce\Entity\Product $product) use ($visitor): bool
		{
			return ($product->canView() && !$visitor->isIgnoring($product->user_id));
		});
	}
	
	/**
	 * @param \DBTech\eCommerce\Finder\Product $finder
	 * @param array $filters
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function applyFilters(\DBTech\eCommerce\Finder\Product $finder, array $filters)
	{
		$visitor = \XF::visitor();
		if (!empty($filters['watched']))
		{
			$finder->watchedOnly($visitor->user_id);
		}
	}
	
	/**
	 * @return int
	 */
	public function getResultsPerPage(): int
	{
		return 20;
	}
	
	/**
	 * @return bool
	 */
	public function isAvailable(): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor->canViewDbtechEcommerceProducts();
	}
}