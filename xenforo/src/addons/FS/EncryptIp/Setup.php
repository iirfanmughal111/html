<?php

namespace FS\EncryptIp;

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
	public function installStep1()
	{      
            $sm = $this->schemaManager();

            $sm->alterTable('xf_ip', function(Alter $table)
            {
				$table->addColumn('ip_backup', 'varbinary', 16);
            });

			$sm->alterTable('xf_ip_match', function(Alter $table)
            {
				$table->addColumn('first_byte_backup', 'binary', 1)->setDefault('');
				$table->addColumn('start_range_backup', 'varbinary', 16)->setDefault('');
				$table->addColumn('end_range_backup', 'varbinary', 16)->setDefault('');	
            });
			
	}
	
	// public function installstep1()
	// {
	// 	$this->alterTable('xf_ip', function (\XF\Db\Schema\Alter $table) {
			
	// 		$table->addColumn('ip_backup', 'varbinary', 16)->nullable();
	// 	});

	// 	$this->alterTable('xf_ip_match', function (\XF\Db\Schema\Alter $table) {
			
	// 		$table->addColumn('first_byte_backup', 'binary', 1)->nullable();
	// 		$table->addColumn('start_range_backup', 'varbinary', 16)->nullable();
	// 		$table->addColumn('end_range_backup', 'varbinary', 16)->nullable();		

	// 	});
	
	// }

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();
		
		$instalServive = \xf::app()->service('FS\EncryptIp:InstallSetup');
		$copy = $instalServive->copyData();
		$this->RestoreColumnsType();
		$decryptData = $instalServive->decryptAllData();
		
		$sm ->alterTable('xf_ip', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['ip_backup']);
		});
		
		$this->alterTable('xf_ip_match', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['first_byte_backup','start_range_backup','end_range_backup']);

		});
		
	}
	
	public function postInstall(array &$stateChanges)
	{
		$sm = $this->schemaManager();


		$instalServive = \xf::app()->service('FS\EncryptIp:InstallSetup');
		$copy = $instalServive->copyData();
		$this->changeColumnsType();
		$encryptData = $instalServive->encryptAllData();
		$this->changeColumnsBackup();
		
	}

	public function changeColumnsType(){
		$this->alterTable('xf_ip', function (\XF\Db\Schema\Alter $table) {
			$table->changeColumn('ip','varchar', 150);
		});
			
		$this->alterTable('xf_ip_match', function (\XF\Db\Schema\Alter $table) {
			$table->changeColumn('first_byte','varchar', 100);
			$table->changeColumn('start_range','varchar', 120);
			$table->changeColumn('end_range','varchar', 120);

		});
	}

	public function RestoreColumnsType(){
		$this->alterTable('xf_ip', function (\XF\Db\Schema\Alter $table) {
			
			$table->changeColumn('ip', 'varbinary', 16);
		});
			
		$this->alterTable('xf_ip_match', function (\XF\Db\Schema\Alter $table) {
			$table->changeColumn('first_byte', 'binary', 1);
			$table->changeColumn('start_range', 'varbinary', 16);
			$table->changeColumn('end_range', 'varbinary', 16);
		});
	}
	public function changeColumnsBackup(){
		$this->alterTable('xf_ip', function (\XF\Db\Schema\Alter $table) {
			$table->changeColumn('ip_backup', 'varchar', 150);
		});
			
		$this->alterTable('xf_ip_match', function (\XF\Db\Schema\Alter $table) {
			$table->changeColumn('first_byte_backup', 'varchar', 100);
			$table->changeColumn('start_range_backup', 'varchar', 120);
			$table->changeColumn('end_range_backup', 'varchar', 120);

		});
	}
	
}