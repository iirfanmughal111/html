<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;

/**
 * @api-group Licenses
 */
class License extends AbstractLoggableEndpoint
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
	 * @api-desc Gets information about the specified license.
	 *
	 * @api-out License $license
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGet(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$license = $this->assertViewableLicense($params->license_key);

		$result = [
			'license' => $license->toApiResult()
		];
		return $this->apiResult($result);
	}

	/**
	 * @param int $id
	 * @param string|array $with
	 *
	 * @return \DBTech\eCommerce\Entity\License|Entity
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableLicense($id, $with = 'api')
	{
		/** @var \DBTech\eCommerce\Entity\License $record */
		$record = $this->finder('DBTech\eCommerce:License')->where('license_key', $id)->fetchOne();
		if (!$record)
		{
			throw $this->exception(
				$this->notFound(\XF::phrase('requested_page_not_found'))
			);
		}
		
		if (\XF::isApiCheckingPermissions() && !$record->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		return $record;
	}
}