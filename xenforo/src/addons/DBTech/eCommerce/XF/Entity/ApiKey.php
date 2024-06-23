<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class ApiKey
 * @package DBTech\eCommerce\XF\Entity
 */
class ApiKey extends XFCP_ApiKey
{
	/**
	 * @return bool
	 */
	public function hasNotifiableChanges()
	{
		return (
			parent::hasNotifiableChanges()
			&& !$this->getOption('dbtech_ecommerce_is_automated')
		);
	}

	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 * @noinspection PhpMissingReturnTypeInspection
	 */
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);
		
		$structure->options['dbtech_ecommerce_is_automated'] = false;

		return $structure;
	}
}