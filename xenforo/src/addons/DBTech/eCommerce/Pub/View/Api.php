<?php

namespace DBTech\eCommerce\Pub\View;

/**
 * Class Api
 *
 * @package DBTech\eCommerce\Pub
 */
class Api extends \XF\Mvc\View
{
	/**
	 * @return array
	 */
	public function renderJson(): array
	{
		return $this->params;
	}
}