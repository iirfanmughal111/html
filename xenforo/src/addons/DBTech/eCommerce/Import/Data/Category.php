<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class Category
 *
 * @package DBTech\eCommerce\Import\Data
 */
class Category extends AbstractEmulatedData
{
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'category';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:Category';
	}
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		\XF::runOnce('dbtEcCategoryImport', function ()
		{
			/** @var \XF\Service\RebuildNestedSet $service */
			$service = \XF::service('XF:RebuildNestedSet', 'DBTech\eCommerce:Category', [
				'parentField' => 'parent_category_id'
			]);
			$service->rebuildNestedSetInfo();
		});
	}
}