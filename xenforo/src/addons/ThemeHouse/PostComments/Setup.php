<?php

namespace ThemeHouse\PostComments;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;

/**
 * Class Setup
 * @package ThemeHouse\PostComments
 */
class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	// ################################## INSTALL ###########################################

	public function installStep1()
	{
		$sm = $this->schemaManager();
		foreach ($this->getAlters() as $table => $schema)
		{
			if ($sm->tableExists($table))
			{
				$sm->alterTable($table, $schema);
			}
		}
	}

	public function installStep2()
	{
		$this->applyGlobalPermission('forum', 'thpostcomments_comment', 'forum', 'like');
	}

	public function installStep3()
	{
		\XF::app()->jobManager()->enqueue('ThemeHouse\PostComments:RebuildCounters');
	}

	// ################################## UNINSTALL ###########################################

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();
		foreach ($this->getReverseAlters() as $table => $schema)
		{
			if ($sm->tableExists($table))
			{
				$sm->alterTable($table, $schema);
			}
		}
	}

	public function uninstallStep2()
	{
		$this->db()->delete('xf_user_alert', 'content_type = ? AND action = ?', ['post', 'thpostcomments_insert']);
	}

	// ################################## UPGRADE ###########################################

	public function upgrade1000231Step1()
	{
		$schemaManager = $this->schemaManager();
		$schemaManager->alterTable('xf_post', function (Alter $table) {
			$table->renameColumn('parent_post_id', 'thpostcomments_parent_post_id');
			$table->renameColumn('root_post_id', 'thpostcomments_root_post_id');
			$table->renameColumn('lft', 'thpostcomments_lft');
			$table->renameColumn('rgt', 'thpostcomments_rgt');
			$table->renameColumn('depth', 'thpostcomments_depth');
		});

		\XF::app()->jobManager()->enqueue('ThemeHouse\PostComments:RebuildCounters');
	}

	public function upgrade1000231Step2()
	{
		$schemaManager = $this->schemaManager();
		$schemaManager->alterTable('xf_thread', function (Alter $table) {
			$table->addColumn('thpostcomments_root_reply_count', 'int')->setDefault(0);
		});

		\XF::app()->jobManager()->enqueue('ThemeHouse\PostComments:RebuildCounters');
	}

	// ################################## DATA ###########################################

	/**
	 * @return array
	 */
	protected function getAlters()
	{
		$alters = [];

		$alters['xf_post'] = function (Alter $table) {
			$table->addColumn('thpostcomments_parent_post_id', 'int')->setDefault(0);
			$table->addColumn('thpostcomments_root_post_id', 'int')->setDefault(0);
			$table->addColumn('thpostcomments_lft', 'int')->setDefault(0);
			$table->addColumn('thpostcomments_rgt', 'int')->setDefault(0);
			$table->addColumn('thpostcomments_depth', 'smallint', 5)->setDefault(0);
			$table->addKey('thpostcomments_parent_post_id');
			$table->addKey('thpostcomments_root_post_id');
			$table->addKey('thpostcomments_lft');
		};

		$alters['xf_thread'] = function (Alter $table) {
			$table->addColumn('thpostcomments_root_reply_count', 'int')->setDefault(0);
		};

		return $alters;
	}

	/**
	 * @return array
	 */
	protected function getReverseAlters()
	{
		$alters = [];

		$alters['xf_post'] = function (Alter $table) {
			$table->dropColumns([
				'thpostcomments_parent_post_id',
				'thpostcomments_root_post_id',
				'thpostcomments_lft',
				'thpostcomments_rgt',
				'thpostcomments_depth',
			]);
		};

		$alters['xf_thread'] = function (Alter $table) {
			$table->dropColumns([
				'thpostcomments_root_reply_count'
			]);
		};

		return $alters;
	}
}
