<?php

namespace XenBulletins\Tournament;

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
        $this->schemaManager()->createTable('xf_tournament',function(Create $table)
        {
            $table->addColumn('tourn_id', 'int', '255')->autoIncrement();
            $table->addColumn('tourn_domain', 'varchar', '255');
            $table->addColumn('tourn_title', 'varchar', '255');
            $table->addColumn('tourn_startdate', 'int', '10')->setDefault(0);
            $table->addColumn('tourn_enddate', 'int', '10')->setDefault(0);
            $table->addColumn('tourn_starttime', 'int', '10')->setDefault(0);
            $table->addColumn('tourn_endtime', 'int', '10')->setDefault(0);
            $table->addColumn('tourn_icon', 'varchar', '255')->nullable();
            $table->addColumn('tourn_header', 'varchar', '255')->nullable();
            $table->addColumn('tourn_main_price', 'varchar', '255');
            $table->addColumn('tourn_desc', 'varchar', '255');
            $table->addColumn('tourn_prizes', 'blob', '255')->nullable();
            $table->addColumn('conversation', 'varchar', '10')->setDefault(0);
            $table->addPrimaryKey('tourn_id');      
        });
    }
    
    public function installstep2()
    {
        $this->schemaManager()->createTable('xf_tournament_register',function(Create $table)
        { 
            
            $table->addColumn('reg_id', 'int', '255')->autoIncrement();
            $table->addColumn('user_id', 'int', '10')->setDefault(0);
            $table->addColumn('tourn_id', 'int', '10')->setDefault(0);
            $table->addColumn('current_time', 'int', '10')->setDefault(0);
            $table->addPrimaryKey('reg_id');
            
               });
            
        
        
    }
    
    

    public function uninstallStep1()
    {
        $sm = $this->schemaManager();
        $sm->dropTable('xf_tournament');
    }
}