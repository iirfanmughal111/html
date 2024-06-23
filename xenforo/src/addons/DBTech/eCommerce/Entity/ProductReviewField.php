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
 * @property string $wrapper_template
 *
 * GETTERS
 * @property \XF\Phrase $title
 * @property \XF\Phrase $description
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Entity\Phrase $MasterDescription
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\CategoryReviewField[] $CategoryReviewFields
 */
class ProductReviewField extends AbstractField
{
	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:ProductReviewField';
	}

	/**
	 * @return string
	 */
	protected static function getPhrasePrefix(): string
	{
		return 'dbtech_ecommerce_product_review_field';
	}

	protected function _postDelete()
	{
		/** @var \DBTech\eCommerce\Repository\CategoryField $repo */
		$repo = $this->repository('DBTech\eCommerce:CategoryReviewField');
		$repo->removeFieldAssociations($this);

		$this->db()->delete('xf_dbtech_ecommerce_product_review_field_value', 'field_id = ?', $this->field_id);

		parent::_postDelete();
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
			'xf_dbtech_ecommerce_product_review_field',
			'DBTech\eCommerce:ProductReviewField',
			[
				'groups'               => ['above_review', 'below_review'],
				'has_wrapper_template' => true
			]
		);

		$structure->relations['CategoryReviewFields'] = [
			'entity'     => 'DBTech\eCommerce:CategoryReviewField',
			'type'       => self::TO_MANY,
			'conditions' => 'field_id'
		];

		return $structure;
	}
}