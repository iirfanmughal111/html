<?php

namespace FS\ForumGroups;

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
		$this->alterTable('xf_node', function (\XF\Db\Schema\Alter $table) {
			$table->addColumn('user_id', 'int')->setDefault(0);
			$table->addColumn('room_path', 'mediumtext')->nullable();
			$table->addColumn('avatar_attachment_id', 'int')->setDefault(0);
			$table->addColumn('cover_attachment_id', 'int')->setDefault(0);
			$table->addColumn('cover_crop_data', 'blob');
		});
	}

	public function uninstallStep1()
	{
		$this->schemaManager()->alterTable('xf_node', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['user_id']);
			$table->dropColumns(['avatar_attachment_id']);
			$table->dropColumns(['cover_attachment_id']);
			$table->dropColumns(['cover_crop_data']);
		});
	}
}
