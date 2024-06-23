<?php

namespace DBTech\eCommerce\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Report
 */
class Product extends AbstractHandler
{
	/**
	 * @param Report $report
	 *
	 * @return bool
	 */
	protected function canViewContent(Report $report): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		$categoryId = $report->content_info['product']['product_category_id'];
		$productId = $report->content_info['product']['product_id'];
		
		if (!method_exists($visitor, 'hasDbtechEcommerceCategoryPermission'))
		{
			return false;
		}
		
		if (!method_exists($visitor, 'hasDbtechEcommerceProductPermission'))
		{
			return false;
		}
		
		return (
			$visitor->hasDbtechEcommerceCategoryPermission($categoryId, 'view')
			&& $visitor->hasDbtechEcommerceProductPermission($productId, 'view')
		);
	}
	
	/**
	 * @param Report $report
	 *
	 * @return bool
	 */
	protected function canActionContent(Report $report): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		$categoryId = $report->content_info['product']['product_category_id'];

		if (!method_exists($visitor, 'hasDbtechEcommerceCategoryPermission'))
		{
			return false;
		}

		return (
			$visitor->hasDbtechEcommerceCategoryPermission($categoryId, 'deleteAny')
			|| $visitor->hasDbtechEcommerceCategoryPermission($categoryId, 'editAny')
		);
	}
	
	/**
	 * @param Report $report
	 * @param Entity $content
	 */
	public function setupReportEntityContent(Report $report, Entity $content)
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $content;
		$category = $product->Category;
		
		if (!empty($product->prefix_id))
		{
			$title = $product->Prefix->title . ' - ' . $product->title;
		}
		else
		{
			$title = $product->title;
		}

		$report->content_user_id = $product->user_id;
		$report->content_info = [
			'product' => [
				'product_id' => $product->product_id,
				'title' => $title,
				'message' => $product->description->render(),
				'prefix_id' => $product->prefix_id,
				'product_category_id' => $product->product_category_id,
				'user_id' => $product->user_id,
				'username' => $product->username
			],
			'category' => [
				'category_id' => $category->category_id,
				'title' => $category->title
			]
		];
	}
	
	/**
	 * @param Report $report
	 *
	 * @return \XF\Phrase
	 */
	public function getContentTitle(Report $report): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_product_in_x', [
			'title' => \XF::app()->stringFormatter()->censorText($report->content_info['category']['title'])
		]);
	}
	
	/**
	 * @param Report $report
	 *
	 * @return mixed
	 */
	public function getContentMessage(Report $report)
	{
		return $report->content_info['product']['message'];
	}
	
	/**
	 * @param Report $report
	 *
	 * @return mixed|string
	 */
	public function getContentLink(Report $report): string
	{
		$info = $report->content_info;

		return \XF::app()->router()->buildLink(
			'canonical:dbtech-ecommerce',
			[
				'product_id' => $info['product']['product_id'],
				'product_title' => $info['product']['title']
			]
		);
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return ['Category'];
	}
}