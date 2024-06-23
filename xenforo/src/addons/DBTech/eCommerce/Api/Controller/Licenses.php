<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;

/**
 * @api-group Licenses
 */
class Licenses extends AbstractLoggableEndpoint
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		parent::preDispatchController($action, $params);
		
		$this->assertApiScopeByRequestMethod('dbtech_ecommerce_license');
		$this->assertRegisteredUser();
	}
	
	/**
	 * @api-desc Gets the list of licenses owned by the current user.
	 *
	 * @api-in array $category_ids Only fetch licenses belonging to products within these categories.
	 * @api-in array $platforms Only fetch licenses belonging to products matching these platforms.
	 *
	 * @api-out License[] $licenses List of all owned licenses.
	 *
	 * @api-error you_have_not_purchased_any_licenses_yet Triggered if no licenses match the search parameters.
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\Error
	 */
	public function actionGet()
	{
		$categoryIds = $this->filter('category_ids', 'array-uint');
		
		/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
		$licenseRepo = $this->repository('DBTech\eCommerce:License');
		
		$licenseFinder = $licenseRepo->findLicensesByUser(
			\XF::visitor()->user_id,
			$categoryIds ?: null,
			['allowOwnPending' => false]
		);
		
		$platformFilters = $this->filter('platforms', 'array-str');
		if ($platformFilters)
		{
			$filterAssociations = $this->finder('DBTech\eCommerce:ProductFilterMap')
				->where('filter_id', $platformFilters)
			;
			
			$licenseFinder->where('product_id', $filterAssociations->fetch()
				->pluckNamed('product_id', 'product_id'));
		}
		
		/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\AbstractCollection $stickyThreads */
		$licenses = $licenseFinder->fetch()
			->filterViewable()
		;
		$licenses = $licenseRepo->filterLicensesForApiResponse($licenses);
		
		if (!$licenses->count())
		{
			return $this->apiError(
				\XF::phrase('api_error.dbtech_ecommerce_you_have_not_purchased_any_licenses_yet'),
				'you_have_not_purchased_any_licenses_yet',
				[],
				402
			);
		}
		
		$result = [
			'licenses' => $licenses->toApiResults(Entity::VERBOSITY_VERBOSE, [
				'with_product' => true,
				'with_latest_version' => true
			])
		];
		return $this->apiResult($result);
	}
}