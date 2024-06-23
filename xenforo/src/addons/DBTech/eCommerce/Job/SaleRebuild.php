<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractJob;

/**
 * Class SaleRebuild
 *
 * @package DBTech\eCommerce\Job
 */
class SaleRebuild extends AbstractJob
{
	/** @var array */
	protected $defaultData = [
		'cleaned' => false
	];
	
	/**
	 * @param $maxRunTime
	 *
	 * @return \XF\Job\JobResult
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function run($maxRunTime): \XF\Job\JobResult
	{
		// We intentionally don't check max run time as support for multiple steps is very difficult
		// and it's highly unlikely this will go on for long anyway
		
		$em = $this->app->em();
		$app = \XF::app();
		
		if (!$this->data['cleaned'])
		{
			/** @var \DBTech\eCommerce\Repository\Sale $saleRepo */
			$saleRepo = $app->repository('DBTech\eCommerce:Sale');
			$saleRepo->resetProductSales();
			$saleRepo->advanceSaleDates();
			$saleRepo->updateRecurringSales();
			
			/** @var \DBTech\eCommerce\Repository\Product $productRepo */
			$productRepo = $app->repository('DBTech\eCommerce:Product');
			$productRepo->resetTemporaryProductFeatures();
			
			$this->data['cleaned'] = true;
		}
		
		/** @var \DBTech\eCommerce\Entity\Sale[] $sales */
		$sales = $app->finder('DBTech\eCommerce:Sale')
			->onlyActive()
			->fetch();
		
		/** @var \DBTech\eCommerce\Finder\Product $productFinder */
		$productFinder = $app->finder('DBTech\eCommerce:Product');
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		foreach ($productFinder->fetch() AS $product)
		{
			if (!$product->Costs)
			{
				continue;
			}
			
			if (!$product->Costs->last())
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Entity\Sale $bestSale */
			$bestSale = null;
			$lowestCost = $baseCost = $product->Costs->last()->cost_amount;
			
			foreach ($sales as $sale)
			{
				$discountedCost = $sale->getDiscountedCost($product, $baseCost);
				if ($discountedCost < $lowestCost)
				{
					// We found a better sale than what we had
					$lowestCost = $discountedCost;
					$bestSale = $sale;
				}
			}
			
			if ($bestSale !== null)
			{
				/** @var \DBTech\eCommerce\Entity\ProductSale $productSale */
				$productSale = $em->create('DBTech\eCommerce:ProductSale');
				$productSale->product_id = $product->product_id;
				$productSale->sale_type = $bestSale->sale_type;
				$productSale->set('sale_' . $bestSale->sale_type, $bestSale->getApplicableDiscount($product));
				$productSale->save();
				
				if ($bestSale->feature_products)
				{
					/** @var \DBTech\eCommerce\Entity\ProductFeatureTemp $featureTemp */
					$featureTemp = $em->create('DBTech\eCommerce:ProductFeatureTemp');
					$featureTemp->product_id = $product->product_id;
					$featureTemp->feature_key = 'sale-' . $bestSale->sale_id;
					$featureTemp->create_date = $bestSale->start_date;
					$featureTemp->expiry_date = $bestSale->end_date;
					$featureTemp->save();
					
					$product->is_featured = true;
					$product->save();
				}
			}
		}

		return $this->complete();
	}
	
	/**
	 * @return string
	 */
	public function getStatusMessage(): string
	{
		$actionPhrase = \XF::phrase('rebuilding');
		$typePhrase = \XF::phrase('dbtech_ecommerce_sale');
		return sprintf('%s... %s', $actionPhrase, $typePhrase);
	}
	
	/**
	 * @return bool
	 */
	public function canCancel(): bool
	{
		return false;
	}
	
	/**
	 * @return bool
	 */
	public function canTriggerByChoice(): bool
	{
		return false;
	}
}