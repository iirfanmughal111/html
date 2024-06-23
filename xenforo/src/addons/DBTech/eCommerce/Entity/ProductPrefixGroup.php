<?php

namespace DBTech\eCommerce\Entity;

use XF\Entity\AbstractPrefixGroup;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $prefix_group_id
 * @property int $display_order
 *
 * GETTERS
 * @property \XF\Phrase|string $title
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductPrefix[] $Prefixes
 */
class ProductPrefixGroup extends AbstractPrefixGroup
{
	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:ProductPrefix';
	}
	
	/**
	 * @return string|void
	 */
	protected static function getContentType(): string
	{
		return 'dbtechEcommerceProduct';
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		self::setupDefaultStructure(
			$structure,
			'xf_dbtech_ecommerce_product_prefix_group',
			'DBTech\eCommerce:ProductPrefixGroup',
			'DBTech\eCommerce:ProductPrefix'
		);

		return $structure;
	}
}