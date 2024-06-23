<?php

namespace Pad;

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


	public function installStepAttendance()
	{
		$this->schemaManager()->createTable('xf_forum_attendance', function (Create $table) {
			$table->addColumn('attendance_id', 'int', '255')->autoIncrement();
			$table->addColumn('user_id', 'int', '255')->nullable();
			$table->addColumn('date', 'int', '255')->nullable();
			$table->addColumn('in_time', 'int', '255')->nullable();
			$table->addColumn('out_time', 'int', '255')->nullable();
			$table->addColumn('comment', 'mediumtext')->nullable();
			// $table->addPrimaryKey('attendance_id');


		});
	}

	public function uninstallStepAttendance()
	{
		$sm = $this->schemaManager();
		$sm->dropTable('xf_forum_attendance');
	}


}