<?php

namespace FS\HideUsernames;

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
		$this->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {
			$table->addColumn('random_name', 'mediumtext')->nullable()->setDefault(null);
		});
	}

	public function uninstallStep1()
	{
		$this->schemaManager()->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['random_name']);
		});
	}

	public function postInstall(array &$stateChanges)
	{


		$app = \XF::app();

		$jobID = "random_usernames";

		$app->jobManager()->enqueueUnique($jobID, 'FS\HideUsernames:RandomUsername', [], false);
		// $app->jobManager()->runUnique($jobID, 120);
	}
}
