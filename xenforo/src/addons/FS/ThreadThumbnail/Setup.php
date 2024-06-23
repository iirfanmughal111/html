<?php

namespace FS\ThreadThumbnail;

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
		
		$this->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			$table->addColumn('thumbnail_ext', 'varchar',20)->nullable();
            $table->addColumn('thumbnail_title', 'varchar', 200)->nullable();
			
		});

	}

	public function uninstallStep1()
	{
		

		$this->schemaManager()->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			
			$table->dropColumns(['thumbnail_ext']);
			$table->dropColumns(['thumbnail_title']);
						
		});

		
	}
	
}