<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $product_id
 * @property string $email_format
 * @property string $email_title
 * @property string $email_body
 * @property string $from_name
 * @property string $from_email
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 */
class ProductWelcomeEmail extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_welcome_email';
		$structure->shortName = 'DBTech\eCommerce:ProductWelcomeEmail';
		$structure->primaryKey = 'product_id';
		$structure->columns = [
			'product_id'   => ['type' => self::UINT, 'required' => true],
			'email_format' => [
				'type'          => self::STR, 'default' => 'text',
				'allowedValues' => [
					'text', 'html'
				]
			],
			'email_title'  => ['type' => self::STR, 'maxLength' => 250],
			'email_body'   => ['type' => self::STR],
			'from_name'    => ['type' => self::STR, 'maxLength' => 250],
			'from_email'   => ['type' => self::STR, 'maxLength' => 120]
		];
		$structure->getters = [];
		$structure->relations = [
			'Product' => [
				'entity'     => 'DBTech\eCommerce:Product',
				'type'       => self::TO_ONE,
				'conditions' => 'product_id',
				'primary'    => true
			],
		];

		return $structure;
	}
}