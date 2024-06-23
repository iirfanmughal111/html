<?php

namespace FS\ScheduleBanUser;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;
	public function installstep1()
	{
		$this->createTable('fs_schedule_ban_user', function (\XF\Db\Schema\Create $table)
			{
					$table->addColumn('ban_id', 'int')->autoIncrement();
					$table->addColumn('user_id', 'int')->setDefault(0);
					$table->addColumn('user_banBy_id', 'int')->setDefault(0);
					$table->addColumn('ban_date', 'int')->setDefault(0);
					$table->addColumn('ban_reason', 'text');
			});
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();
		$sm->dropTable('fs_schedule_ban_user');
	}
}