<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class Post
 * @package DBTech\eCommerce\XF\Entity
 */
class Post extends XFCP_Post
{
	/**
	 * @param $context
	 * @param $type
	 *
	 * @return array
	 */
	public function getBbCodeRenderOptions($context, $type)
	{
		$options = parent::getBbCodeRenderOptions($context, $type);
		$options['dbtechEcommerceProducts'] = $this->DBTechEcommerceProducts;
		
		return $options;
	}
	
	/**
	 * @return array|null
	 */
	public function getDBTechEcommerceProducts()
	{
		return isset($this->_getterCache['DBTechEcommerceProducts']) ? $this->_getterCache['DBTechEcommerceProducts'] : null;
	}
	
	/**
	 * @param array $products
	 */
	public function setDBTechEcommerceProducts(array $products)
	{
		$this->_getterCache['DBTechEcommerceProducts'] = $products;
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
		
		$structure->getters['DBTechEcommerceProducts'] = true;
		
		return $structure;
	}
}