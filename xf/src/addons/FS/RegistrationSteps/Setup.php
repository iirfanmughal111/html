<?php

namespace FS\RegistrationSteps;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup {

    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installstep1() {

        $this->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {
            $table->addColumn('account_type', 'int')->setDefault(1);
            $table->addColumn('is_verify', 'int')->setDefault(1);
            $table->addColumn('activation_id', 'text')->nullable();
        });
                $this->createTable('fs_register_vouch', function (\XF\Db\Schema\Create $table)
        {
            $table->addColumn('vouch_id', 'int')->autoIncrement();
            $table->addColumn('vouch_from_user_id', 'int');
            $table->addColumn('vouch_to_user_id', 'text');
            $table->addColumn('created_at', 'int')->setDefault(0);
        });

        $this->createTable('fs_register_appointments', function (\XF\Db\Schema\Create $table)
        {
                $table->addColumn('appt_id', 'int')->autoIncrement();
                $table->addColumn('time', 'int');
                $table->addColumn('date', 'text');
                $table->addColumn('from_user_id', 'text');
                $table->addColumn('to_user_id', 'text');
                $table->addColumn('contact', 'text');
                $table->addColumn('duration', 'text');
                $table->addColumn('appt_type', 'text');
                $table->addColumn('city', 'text');
                $table->addColumn('rates', 'text');
                $table->addColumn('promotion', 'text');
                $table->addColumn('created_at', 'int')->setDefault(0);
        });

        $this->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {

		$table->addColumn('review_for', 'int')->setDefault(0);
		$table->addColumn('is_featured', 'int')->setDefault(0);
	});
    }

    public function upgrade1000600Step1() {

        $this->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {

            $table->addColumn('is_verify', 'int')->setDefault(1);
            $table->addColumn('activation_id', 'text')->nullable();
        });
    }

    public function uninstallStep1() {
        $this->schemaManager()->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {
            $table->dropColumns(['account_type']);
            $table->dropColumns(['is_verify']);
            $table->dropColumns(['activation_id']);
        });
          $this->schemaManager()->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
		$table->dropColumns(['review_for']);
		$table->dropColumns(['is_featured']);
	});
	 $sm = $this->schemaManager();
        $sm->dropTable('fs_register_vouch');
        $sm->dropTable('fs_register_appointments');
	
	
    }
}
