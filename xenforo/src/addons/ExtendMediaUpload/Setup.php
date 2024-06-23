<?php

namespace ExtendMediaUpload;

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
        $this->schemaManager()->alterTable('ewr_medio_media', function(Alter $table)
        {
            $table->addColumn('uniq_code', 'varchar','100')->setDefault(0);
            $table->addColumn('status', 'varchar','100')->setDefault("process");
        });
    }
    
}