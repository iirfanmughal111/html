<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $product_rating_id
 * @property string $field_id
 * @property string $field_value
 */
class ProductReviewFieldValue extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_review_field_value';
		$structure->shortName = 'DBTech\eCommerce:ProductReviewFieldValue';
		$structure->primaryKey = ['product_rating_id', 'field_id'];
		$structure->columns = [
			'product_rating_id' => ['type' => self::UINT, 'required' => true],
			'field_id'          => ['type' => self::STR, 'maxLength' => 25, 'match' => 'alphanumeric'],
			'field_value'       => ['type' => self::STR, 'default' => '']
		];
		$structure->getters = [];
		$structure->relations = [];

		return $structure;
	}
}