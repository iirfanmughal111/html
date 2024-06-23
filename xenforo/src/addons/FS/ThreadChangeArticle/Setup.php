<?php

namespace FS\ThreadChangeArticle;

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

	public function installstep1()
	{
		
		$this->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {

			$table->addColumn('is_view_change', 'int')->setDefault(0);
                $table->addColumn('img_ex', 'varchar', 200)->nullable();
		});

	}

	public function uninstallStep1()
	{
		$dirname =  sprintf(
			'data://ThreadThumbnail/%d.' ,
			floor($productId / 1000),
		);
		array_map('unlink', glob("$dirname/*.*"));
		rmdir($dirname);
		$this->schemaManager()->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			
			$table->dropColumns(['is_view_change']);
			$table->dropColumns(['img_ex']);
		});

		
	}

	

	
}