<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Service\User;

/**
 * Class ContentChange
 * @package DBTech\eCommerce\XF\Service\User
 */
class ContentChange extends XFCP_ContentChange
{
	protected function stepRebuildFinalCaches()
	{
		parent::stepRebuildFinalCaches();
		
		if ($this->newUserId === null)
		{
			return;
		}
		
		/** @var \DBTech\eCommerce\Repository\License $repo */
		$repo = $this->app->repository('DBTech\eCommerce:License');
		$count = $repo->getUserLicenseCount($this->newUserId);
		
		$this->app->db()->update('xf_user', ['dbtech_ecommerce_license_count' => $count], 'user_id = ?', $this->newUserId);
		
		
		/** @var \DBTech\eCommerce\Repository\Product $repo */
		$repo = $this->app->repository('DBTech\eCommerce:Product');
		$count = $repo->getUserProductCount($this->newUserId);
		
		$this->app->db()->update('xf_user', ['dbtech_ecommerce_product_count' => $count], 'user_id = ?', $this->newUserId);
	}
}