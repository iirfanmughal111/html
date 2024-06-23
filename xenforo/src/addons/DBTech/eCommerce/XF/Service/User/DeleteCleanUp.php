<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Service\User;

/**
 * Class DeleteCleanUp
 * @package DBTech\eCommerce\XF\Service\User
 */
class DeleteCleanUp extends XFCP_DeleteCleanUp
{
	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function stepMiscCleanUp()
	{
		parent::stepMiscCleanUp();
		
		/** @var \DBTech\eCommerce\Entity\DownloadLog $downloadLog */
		$downloadLogs = $this->finder('DBTech\eCommerce:DownloadLog')->where('user_id', $this->userId);
		foreach ($downloadLogs as $downloadLog)
		{
			$downloadLog->delete(false);
		}
		
		/** @var \DBTech\eCommerce\Entity\License $license */
		$licenses = $this->finder('DBTech\eCommerce:License')->where('user_id', $this->userId);
		foreach ($licenses as $license)
		{
			$license->delete(false);
		}
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$products = $this->finder('DBTech\eCommerce:Product')->where('user_id', $this->userId);
		foreach ($products as $product)
		{
			$product->delete(false);
		}
	}
}