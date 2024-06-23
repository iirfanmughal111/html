<?php

namespace XenAddons\Showcase\Entity;

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
 * @property string $wrapper_template
 * @property string $display_group
 * @property array $editable_user_group_ids
 *
 * GETTERS
 * @property \XF\Phrase $title
 * @property \XF\Phrase $description
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Entity\Phrase $MasterDescription
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\CategoryUpdateField[] $CategoryUpdateFields
 */
class UpdateField extends AbstractField
{
	protected function getClassIdentifier()
	{
		return 'XenAddons\Showcase:UpdateField';
	}

	protected static function getPhrasePrefix()
	{
		return 'xa_sc_update_field';
	}

	protected function _postDelete()
	{
		/** @var \XenAddons\Showcase\Repository\CategoryUpdateField $repo */
		$repo = $this->repository('XenAddons\Showcase:CategoryUpdateField');
		$repo->removeFieldAssociations($this);

		$this->db()->delete('xf_xa_sc_update_field_value', 'field_id = ?', $this->field_id);

		parent::_postDelete();
	}

	public static function getStructure(Structure $structure)
	{
		self::setupDefaultStructure(
			$structure,
			'xf_xa_sc_update_field',
			'XenAddons\Showcase:UpdateField',
			[
				'groups' => ['above', 'below', 'self_place'],
				'has_user_group_editable' => true,
				'has_wrapper_template' => true
			]
		);

		$structure->relations['CategoryUpdateFields'] = [
			'entity' => 'XenAddons\Showcase:CategoryUpdateField',
			'type' => self::TO_MANY,
			'conditions' => 'field_id'
		];

		return $structure;
	}
}