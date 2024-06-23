<?php

namespace DBTech\eCommerce\XF\ForumType;

use XF\Entity\Forum;

/**
 * Class Discussion
 *
 * @package DBTech\eCommerce\XF\ForumType
 */
class Discussion extends XFCP_Discussion
{
	/**
	 * @param \XF\Entity\Forum $forum
	 *
	 * @return array
	 */
	public function getExtraAllowedThreadTypes(Forum $forum): array
	{
		$allowed = parent::getExtraAllowedThreadTypes($forum);
		$allowed[] = 'dbtech_ecommerce_product';
		$allowed[] = 'dbtech_ecommerce_download';

		return $allowed;
	}

	/**
	 * @param \XF\Entity\Forum $forum
	 *
	 * @return array
	 */
	public function getCreatableThreadTypes(Forum $forum): array
	{
		$creatable = parent::getCreatableThreadTypes($forum);
		$this->removeProductTypeFromList($creatable);
		$this->removeDownloadTypeFromList($creatable);

		return $creatable;
	}

	/**
	 * @param \XF\Entity\Forum $forum
	 *
	 * @return array
	 */
	public function getFilterableThreadTypes(Forum $forum): array
	{
		$filterable = parent::getFilterableThreadTypes($forum);

		$productTarget = \XF::db()->fetchOne("
			SELECT 1 
			FROM xf_dbtech_ecommerce_category
			WHERE thread_node_id = ?
			LIMIT 1
		", $forum->node_id);
		if (!$productTarget)
		{
			$this->removeProductTypeFromList($filterable);
			$this->removeDownloadTypeFromList($filterable);
		}

		return $filterable;
	}

	/**
	 * @param array $list
	 */
	protected function removeProductTypeFromList(array &$list)
	{
		$productKey = array_search('dbtech_ecommerce_product', $list);
		if ($productKey !== false)
		{
			unset($list[$productKey]);
		}
	}

	/**
	 * @param array $list
	 */
	protected function removeDownloadTypeFromList(array &$list)
	{
		$downloadKey = array_search('dbtech_ecommerce_download', $list);
		if ($downloadKey !== false)
		{
			unset($list[$downloadKey]);
		}
	}
}