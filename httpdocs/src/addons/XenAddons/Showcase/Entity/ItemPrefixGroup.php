<?php

namespace XenAddons\Showcase\Entity;

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
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemPrefix[] $Prefixes
 */
class ItemPrefixGroup extends AbstractPrefixGroup
{
	protected function getClassIdentifier()
	{
		return 'XenAddons\Showcase:ItemPrefix';
	}

	protected static function getContentType()
	{
		return 'sc_item';
	}

	public static function getStructure(Structure $structure)
	{
		self::setupDefaultStructure(
			$structure,
			'xf_xa_sc_item_prefix_group',
			'XenAddons\Showcase:ItemPrefixGroup',
			'XenAddons\Showcase:ItemPrefix'
		);

		return $structure;
	}
}