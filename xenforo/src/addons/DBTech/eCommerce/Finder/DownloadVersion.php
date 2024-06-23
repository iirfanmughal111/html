<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Download
 * @package DBTech\eCommerce\Finder
 */
class DownloadVersion extends Finder
{
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @return self
	 */
	public function inProduct(\DBTech\eCommerce\Entity\Product $product): self
	{
		$this->where('product_id', '=', $product->product_id);

		return $this;
	}

	public function hasDownloads(): self
	{
		$this->whereOr([
			['directories', '!=', ''],
			['attach_count', '>', '0'],
			['download_url', '!=', '']
		]);

		return $this;
	}
}