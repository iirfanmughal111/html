<?php

namespace DBTech\eCommerce\Install;

/**
 * @property \XF\AddOn\AddOn addOn
 * @property \XF\App app
 *
 * @method \XF\Db\AbstractAdapter db()
 * @method \XF\Db\SchemaManager schemaManager()
 * @method \XF\Db\Schema\Column addOrChangeColumn($table, $name, $type = null, $length = null)
 */
trait Upgrade2039970Trait
{
	/**
	 *
	 */
	public function upgrade2030070Step1(): void
	{
		$this->applyTables();
	}
}