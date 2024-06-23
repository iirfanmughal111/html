<?php

namespace FS\AuctionPlugin;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

use FS\AuctionPlugin\Install\Data\MySql;

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

			$table->addColumn('layout_type', 'int')->setDefault(0);
		});

		$this->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {

			$table->addColumn('auction_end_date', 'int')->setDefault(0);
		});

		$this->insertDefaultData();

		$forumService = \xf::app()->service('FS\AuctionPlugin:Auction\ForumAndFields');

		$node = $forumService->createNode();
		$forumService->updateOptionsforum($node->node_id);
		$forumService->createCustomFields($node->node_id);
		$forumService->permissionRebuild();
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		foreach (array_keys($this->getTables()) as $tableName) {
			$sm->dropTable($tableName);
		}

		$this->schemaManager()->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['layout_type']);
		});

		$this->schemaManager()->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['auction_end_date']);
		});

		$forum = \xf::app()->finder('XF:Node')->whereId($this->app()->options()->fs_auction_applicable_forum)->fetchOne();

		$forum->delete();

		$forumFields = \xf::app()->finder('XF:ForumField')->where('node_id', $this->app()->options()->fs_auction_applicable_forum)->fetch();

		if (count($forumFields) > 0) {
			foreach ($forumFields as $forumField) {
				$threadField = \xf::app()->finder('XF:ThreadField')->where('field_id', $forumField->field_id)->fetchOne();

				$threadField->delete();
				$forumField->delete();
			}
		}
	}

	public function insertDefaultData()
	{
		if (!$this->addOn->isInstalled()) {
			$db = $this->app->db();
			$data = $this->getData();
			foreach ($data as $dataQuery) {
				$db->query($dataQuery);
			}

			return count($data);
		}
	}

	protected function getTables()
	{
		$data = new MySql();
		return $data->getTables();
	}

	protected function getData()
	{
		$data = new MySql();
		return $data->getData();
	}
}
