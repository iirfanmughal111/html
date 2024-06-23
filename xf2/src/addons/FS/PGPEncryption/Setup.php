<?php

namespace FS\PGPEncryption;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup {

    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1() {


        $sm = $this->schemaManager();

        $sm->alterTable('xf_user', function (Alter $table) {
            $table->addColumn('public_key', 'mediumtext')->nullable()->setDefault(null);
            $table->addColumn('encrypt_message', 'mediumtext')->nullable()->setDefault(null);
            $table->addColumn('random_message', 'mediumtext')->nullable()->setDefault(null);
            $table->addColumn('pgp_option', 'int')->setDefault(1);
            $table->addColumn('passphrase_option', 'int')->setDefault(1);
            $table->addColumn('passphrase_1', 'mediumtext')->nullable()->setDefault(null);
            $table->addColumn('passphrase_2', 'mediumtext')->nullable()->setDefault(null);
            $table->addColumn('passphrase_3', 'mediumtext')->nullable()->setDefault(null);
            $table->addColumn('verify_pgp', 'int')->nullable()->setDefault(null);
            $table->addColumn('pgp_admin', 'int')->setDefault(0);
        });
    }

    public function uninstallStep1() {
        $sm = $this->schemaManager();

        $sm->alterTable('xf_user', function (Alter $table) {
            $table->dropColumns(['public_key']);
            $table->dropColumns(['encrypt_message']);
            $table->dropColumns(['random_message']);
            $table->dropColumns(['pgp_option']);
            $table->dropColumns(['passphrase_option']);
            $table->dropColumns(['passphrase_1']);
            $table->dropColumns(['passphrase_2']);
             $table->dropColumns(['passphrase_3']);
            $table->dropColumns(['verify_pgp']);
            $table->dropColumns(['pgp_admin']);
        });
    }
    
    public function upgrade2060010Step1() {
      $sm = $this->schemaManager();

        $sm->alterTable('xf_user', function (Alter $table) {
          
        $table->addColumn('pgp_admin', 'int')->setDefault(0);
        $table->addColumn('passphrase_3', 'mediumtext')->nullable()->setDefault(null);
    });
  }
  
  public function upgrade2070010Step1() {
      $sm = $this->schemaManager();

        $sm->alterTable('xf_user', function (Alter $table) {
          
        $table->addColumn('pgp_admin', 'int')->setDefault(0);
        $table->addColumn('passphrase_3', 'mediumtext')->nullable()->setDefault(null);
    });
  }
  
  

}