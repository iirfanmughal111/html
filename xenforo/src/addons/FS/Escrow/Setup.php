<?php

namespace FS\Escrow;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

use FS\Escrow\Install\Data\MySql;


class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installstep1()
	{
		$sm = $this->schemaManager();

		foreach ($this->getTables() as $tableName => $callback) {
			$sm->createTable($tableName, $callback);
		}

		$this->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {
			$table->addColumn('deposit_amount', 'VARCHAR', '255')->setDefault(0);
			$table->addColumn('crypto_address', 'VARCHAR', 220)->nullable();
			$table->addColumn('public_key', 'mediumtext')->nullable()->setDefault(null);
            $table->addColumn('encrypt_message', 'mediumtext')->nullable()->setDefault(null);
            $table->addColumn('random_message', 'mediumtext')->nullable()->setDefault(null);
		});
		$this->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {

			$table->addColumn('escrow_id', 'int')->setDefault(0);
		});

		
	}

	public function postInstall(array &$stateChanges)
	{
		$forumService = \xf::app()->service('FS\Escrow:Escrow\createForum');
		$node = $forumService->createNode();
		$forumService->updateOptionsforum($node->node_id);
		$forumService->permissionRebuild();
		
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		foreach (array_keys($this->getTables()) as $tableName) {
			$sm->dropTable($tableName);
		}

		$this->schemaManager()->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['deposit_amount']);
			$table->dropColumns(['crypto_address']);
			$table->dropColumns(['public_key']);
            $table->dropColumns(['encrypt_message']);
            $table->dropColumns(['random_message']);	
				
		});
		$this->schemaManager()->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['escrow_id']);
		});

		$forum = \xf::app()->finder('XF:Node')->whereId(intval($this->app()->options()->fs_escrow_applicable_forum))->fetchOne();

		if ($forum) {
			$forum->delete();
		}
	}

	protected function getTables()
	{
		$data = new MySql();
		return $data->getTables();
	}
}