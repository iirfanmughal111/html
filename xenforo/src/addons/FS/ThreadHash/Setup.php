<?php

namespace FS\ThreadHash;

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
			$table->addColumn('thread_hash', 'VARCHAR', 255)->nullable();
		});

	//	$this->changeRouteFormat(':int<thread_id,title-thread_hash>/:page');

	}

	public function uninstallStep1()
	{	
	//	$this->changeRouteFormat(':int<thread_id,title>/:page');
		
		$this->schemaManager()->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['thread_hash']);
		});
		
	}

	public function changeRouteFormat($format)
    {
        $app = \xf::app();
        $route = $app->find('XF:Route', 185);
        $route->format = $format;
        $route->save();
    }
	public function postInstall(array &$stateChanges)
	{
		$HashGenerator = \XF::app()->service('FS\ThreadHash:HashGenerator');
		$hash = $HashGenerator->ExistedThreadHash();
		
	}

	
}