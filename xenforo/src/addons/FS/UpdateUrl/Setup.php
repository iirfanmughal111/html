<?php

namespace FS\UpdateUrl;

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
			$table->addColumn('url_string', 'VARCHAR', 255)->nullable();
			$table->changeColumn('title', 'TEXT');

		});

		$this->alterTable('xf_forum', function (\XF\Db\Schema\Alter $table) {
			// $table->dropColumns(['last_thread_title']);
			// $table->addColumn('last_thread_title', 'TEXT');


			$table->changeColumn('last_thread_title','TEXT')->setDefault(null);


		});
		$this->changeRouteFormat(':int<thread_id,url_string>/:page');

	}

	public function uninstallStep1()
	{	
		$this->changeRouteFormat(':int<thread_id,title>/:page');
		
		$this->schemaManager()->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['url_string']);
			$table->changeColumn('title', 'VARCHAR',150);
		});
		
		$this->alterTable('xf_forum', function (\XF\Db\Schema\Alter $table) {
			// $table->dropColumns(['last_thread_title']);
			 $table->changeColumn('last_thread_title', 'varchar', 150)->setDefault('')->comment('Title of thread most recent post is in');

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
		$this->changeURlFormat();
		
	}

	public function changeURlFormat(){
		$threads = \xf::app()->Finder('XF:Thread')->fetch();
        $options = $this->app()->options(); 
		if (count($threads) > 0){
            foreach($threads as $thread){
                $thread->fastUpdate('url_string', substr($thread->title,0,$options->fs_updateUrl_limit));
            }
		}
	}
}