<?php

namespace DBTech\eCommerce\Entity;

use XF\Entity\AbstractField;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property string $field_id
 * @property int $display_order
 * @property string $field_type
 * @property array $field_choices
 * @property string $match_type
 * @property array $match_params
 * @property int $max_length
 * @property bool $required
 * @property string $display_template
 * @property string $display_group
 *
 * GETTERS
 * @property \XF\Phrase $title
 * @property \XF\Phrase $description
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Entity\Phrase $MasterDescription
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\OrderFieldMap[] $OrderFields
 */
class OrderField extends AbstractField
{
	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:OrderField';
	}

	/**
	 * @return string|void
	 */
	protected static function getPhrasePrefix(): string
	{
		return 'dbtech_ecommerce_product_field';
	}

	protected function _postDelete()
	{
		/** @var \DBTech\eCommerce\Repository\OrderFieldMap $repo */
		$repo = $this->repository('DBTech\eCommerce:OrderFieldMap');
		$repo->removeFieldAssociations($this);
		
		$this->db()->delete('xf_dbtech_ecommerce_order_field_value', 'field_id = ?', $this->field_id);
		
		parent::_postDelete();
	}
	
	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-see \XF\Entity\AbstractField::setupApiResultData()
	 *
	 * @api-type OrderField
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
		self::setupDefaultStructure(
			$structure,
			'xf_dbtech_ecommerce_order_field',
			'DBTech\eCommerce:OrderField',
			[
				'groups' => ['above_terms']
			]
		);
		
		$structure->relations['OrderFields'] = [
			'entity' => 'DBTech\eCommerce:OrderFieldMap',
			'type' => self::TO_MANY,
			'conditions' => 'field_id'
		];

		return $structure;
	}
}