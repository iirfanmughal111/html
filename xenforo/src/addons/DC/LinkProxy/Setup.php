<?php

namespace DC\LinkProxy;

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
        $this->createTabeForLinks();

    }
    

    public function uninstallStep1() 
    {
        $sm = $this->schemaManager();
		$sm->dropTable('fs_link_Proxy_list');
    }

    protected function createTabeForLinks(){
        
        $this->createTable('fs_link_Proxy_list', function (\XF\Db\Schema\Create $table)
        {
            $table->addColumn('list_id', 'int')->autoIncrement();
            $table->addColumn('user_group_id', 'int')->nullable();
            $table->addColumn('redirect_time', 'int')->nullable();
            $table->addColumn('link_redirect_html', 'text')->nullable();
        });
    
    }
    
    public function upgrade1010400Step1() 
    {            
        $this->createTabeForLinks();
      
    }
   
}