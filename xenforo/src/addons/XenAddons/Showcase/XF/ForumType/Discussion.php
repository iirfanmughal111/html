<?php

namespace XenAddons\Showcase\XF\ForumType;

use XF\Entity\Forum;

class Discussion extends XFCP_Discussion
{
	public function getExtraAllowedThreadTypes(Forum $forum): array
	{
		$allowed = parent::getExtraAllowedThreadTypes($forum);
		$allowed[] = 'sc_item';

		return $allowed;
	}

	public function getCreatableThreadTypes(Forum $forum): array
	{
		$creatable = parent::getCreatableThreadTypes($forum);
		$this->removeScItemTypeFromList($creatable);

		return $creatable;
	}

	public function getFilterableThreadTypes(Forum $forum): array
	{
		$filterable = parent::getFilterableThreadTypes($forum);

		$scItemTarget = \XF::db()->fetchOne("
			SELECT 1
			FROM xf_xa_sc_category
			WHERE thread_node_id = ?
			LIMIT 1
		", $forum->node_id);
		if (!$scItemTarget)
		{
			$this->removeScItemTypeFromList($filterable);
		}

		return $filterable;
	}

	protected function removeScItemTypeFromList(array &$list)
	{
		$scItemKey = array_search('sc_item', $list);
		if ($scItemKey !== false)
		{
			unset($list[$scItemKey]);
		}
	}
}