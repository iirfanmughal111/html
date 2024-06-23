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
 * @property string $user_editable
 * @property bool $moderator_editable
 *
 * GETTERS
 * @property \XF\Phrase $title
 * @property \XF\Phrase $description
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Entity\Phrase $MasterDescription
 */
class LicenseField extends AbstractField
{
	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:LicenseField';
	}

	/**
	 * @return string|void
	 */
	protected static function getPhrasePrefix(): string
	{
		return 'dbtech_ecommerce_license_field';
	}

	/**
	 *
	 */
	protected function _postDelete()
	{
		parent::_postDelete();
		
		$db = $this->db();
		$db->delete('xf_dbtech_ecommerce_license_field_value', 'field_id = ?', $this->field_id);
		$db->delete('xf_dbtech_ecommerce_download_log_field_value', 'field_id = ?', $this->field_id);
		$db->delete('xf_change_log', 'content_type = \'dbtech_ecommerce_license\' AND field = ?', "license_fields:{$this->field_id}");
	}
	
	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-see \XF\Entity\AbstractField::setupApiResultData()
	 *
	 * @api-type LicenseField
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
			'xf_dbtech_ecommerce_license_field',
			'DBTech\eCommerce:LicenseField',
			[
				'groups' => ['hidden', 'info', 'list'],
				'has_user_editable' => true,
				'has_user_editable_once' => true,
				'has_moderator_editable' => true
			]
		);

		return $structure;
	}
}