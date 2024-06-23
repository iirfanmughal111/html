<?php

namespace DBTech\Credits\Install;

use XF\Db\Schema\Alter;

/**
 * @property \XF\AddOn\AddOn addOn
 * @property \XF\App app
 *
 * @method \XF\Db\AbstractAdapter db()
 * @method \XF\Db\SchemaManager schemaManager()
 * @method \XF\Db\Schema\Column addOrChangeColumn($table, $name, $type = null, $length = null)
 */
trait Upgrade905089970Trait
{
	/**
	 *
	 */
	public function upgrade905080070Step1()
	{
		$this->applyTables();
	}
}