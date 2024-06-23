<?php

namespace FS\DropdownReply;

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



//		$this->createTable('fs_dropdown_reply', function (\XF\Db\Schema\Create $table) {
//			$table->addColumn('dropdown_reply_id', 'int')->autoIncrement();
//			$table->addColumn('thread_id', 'int')->setDefault(0);
//			$table->addColumn('options', 'blob');
//			$table->addColumn('status', 'int')->setDefault(1);
//		});
            
            $this->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			
			$table->addColumn('dropdwon_options', 'blob')->nullable();
			$table->addColumn('is_dropdown_active', 'int')->setDefault(0);
		});
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();
		$sm->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['dropdwon_options','is_dropdown_active']);
		});
	}
}