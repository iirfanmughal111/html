<?php

namespace DBTech\eCommerce\Entity;

use XF\Entity\AbstractPrefix;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $prefix_id
 * @property int $prefix_group_id
 * @property int $display_order
 * @property int $materialized_order
 * @property string $css_class
 * @property array $allowed_user_group_ids
 *
 * GETTERS
 * @property string|\XF\Phrase $title
 * @property bool $has_usage_help
 * @property array $category_ids
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \DBTech\eCommerce\Entity\ProductPrefixGroup $PrefixGroup
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\CategoryPrefix[] $CategoryPrefixes
 */
class ProductPrefix extends AbstractPrefix
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
	 * @return array
	 */
	public function getCategoryIds(): array
	{
		if (!$this->prefix_id)
		{
			return [];
		}

		return $this->db()->fetchAllColumn('
			SELECT category_id
			FROM xf_dbtech_ecommerce_category_prefix
			WHERE prefix_id = ?
		', $this->prefix_id);
	}

	protected function _postDelete()
	{
		parent::_postDelete();

		/** @var \DBTech\eCommerce\Repository\CategoryPrefix $categoryPrefixRepo */
		$categoryPrefixRepo = $this->repository('DBTech\eCommerce:CategoryPrefix');
		$categoryPrefixRepo->removePrefixAssociations($this);
	}
	
	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-see \XF\Entity\AbstractPrefix::setupApiResultData()
	 *
	 * @api-type ProductPrefix
	 */
	protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result,
		$verbosity = self::VERBOSITY_NORMAL,
		array $options = []
	) {
		parent::setupApiResultData($result, $verbosity, $options);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		self::setupDefaultStructure($structure, 'xf_dbtech_ecommerce_product_prefix', 'DBTech\eCommerce:ProductPrefix');

		$structure->getters['category_ids'] = true;

		$structure->relations['CategoryPrefixes'] = [
			'entity' => 'DBTech\eCommerce:CategoryPrefix',
			'type' => self::TO_MANY,
			'conditions' => 'prefix_id'
		];

		return $structure;
	}
}