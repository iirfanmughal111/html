<?php

namespace Banxix\BumpThread;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	// ################################## INSTALL ###########################################

	public function installStep1()
	{
		$sm = $this->schemaManager();

		foreach ($this->getTables() as $tableName => $callback)
		{
			$sm->createTable($tableName, $callback);
		}
	}

	public function installStep2()
	{
		$sm = $this->schemaManager();
		foreach ($this->getAlters() as $table => $schema)
		{
			if ($sm->tableExists($table))
			{
				$sm->alterTable($table, $schema);
			}
		}
	}

	// ################################## UNINSTALL ###########################################

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		foreach (array_keys($this->getTables()) as $tableName)
		{
			$sm->dropTable($tableName);
		}
	}

	public function uninstallStep2()
	{
		$sm = $this->schemaManager();
		foreach ($this->getReverseAlters() as $table => $schema)
		{
			if ($sm->tableExists($table))
			{
				$sm->alterTable($table, $schema);
			}
		}
	}

	// ################################## UPGRADE ###########################################

	public function upgrade2010102Step1()
	{
		$this->schemaManager()->alterTable('xf_thread', function (Alter $table) {
			$table->addColumn('bump_thread_disabled', 'tinyint')->setDefault(0);
		});
	}

	// ################################## DATA ###########################################

	/**
	 * @return array
	 */
	protected function getTables(): array
	{
		$tables = [];

		$tables['xf_bump_thread_log'] = function (Create $table) {
			$table->addColumn('id', 'int')->autoIncrement(true);
			$table->addColumn('thread_id', 'int');
			$table->addColumn('user_id', 'int');
			$table->addColumn('bump_date', 'int');
			$table->addPrimaryKey('id');

			$table->addKey('thread_id', "xf_bump_thread_log_thread_id");
			$table->addKey('user_id', "xf_bump_thread_log_user_id");
		};

		return $tables;
	}

	protected function getAlters()
	{
		$alters = [];

		$alters['xf_thread'] = function (Alter $table) {
			$table->addColumn('bump_thread_disabled', 'tinyint')->setDefault(0);
		};

		return $alters;
	}

	/**
	 * @return array
	 */
	protected function getReverseAlters()
	{
		$alters = [];

		$alters['xf_thread'] = function (Alter $table) {
			$table->dropColumns([
				'bump_thread_disabled'
			]);
		};

		return $alters;
	}
}