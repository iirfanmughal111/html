<?php

namespace FS\Escrow\Install\Data;

use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{

    public function getTables()
    {
        $tables = [];

        $tables['fs_escrow_transaction'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('transaction_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->setDefault(0);
            $table->addColumn('to_user', 'int')->setDefault(0);
            $table->addColumn('escrow_id', 'int')->setDefault(0);
            // $table->addColumn('transaction_amount', 'int')->setDefault(0);
        //    $table->addColumn('transaction_amount', 'decimal', '10,2');
            $table->addColumn('transaction_amount', 'varchar', 255)->nullable();

            $table->addColumn('transaction_type', 'varchar', 100)->nullable();
            // $table->addColumn('current_amount', 'int')->setDefault(0);
      //      $table->addColumn('current_amount', 'decimal', '10,2');
            $table->addColumn('current_amount', 'varchar', 255)->nullable();

            
            $table->addColumn('created_at', 'int')->setDefault(0);
            $table->addColumn('status', 'int')->setDefault(0);
            $table->addColumn('conversation_id', 'int')->setDefault(0);
            $table->addPrimaryKey('transaction_id');
        };

        $tables['fs_escrow_bithide_transaction'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('Id', 'int')->autoIncrement();
            $table->addColumn('Type', 'int')->setDefault(0);
            $table->addColumn('Date', 'varchar','200')->nullable();
            $table->addColumn('TxId', 'varchar',255)->nullable();
            $table->addColumn('Cryptocurrency', 'varchar',150)->nullable();
            $table->addColumn('MerchantId', 'int')->setDefault(0);
            $table->addColumn('MerchantName', 'varchar',150)->nullable();
            $table->addColumn('InitiatorId', 'varchar',150)->setDefault(0);
            $table->addColumn('Initiator', 'varchar',150)->setDefault(0);
            $table->addColumn('Amount', 'decimal', '10,6')->setDefault(0);
            $table->addColumn('AmountUSD', 'decimal', '10,6')->setDefault(0);
            $table->addColumn('Rate', 'decimal', '10,2')->setDefault(0);
            $table->addColumn('Commission', 'decimal', '10,6')->setDefault(0);
            $table->addColumn('CommissionCurrency', 'varchar',150)->nullable();
            $table->addColumn('AddressAdditionalInfo', 'varchar',150)->nullable();
            $table->addColumn('DestinationAddress', 'varchar',150)->nullable();
            $table->addColumn('SenderAddresses','blob')->nullable()->nullable();
            $table->addColumn('ExternalId', 'varchar',150)->nullable();
            $table->addColumn('Comment','varchar',200)->nullable();
            $table->addColumn('Status', 'int')->setDefault(0);
            $table->addColumn('FailReason', 'varchar',220)->nullable();
            $table->addPrimaryKey('Id');
        };

        $tables['fs_escrow_request_deposit'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('req_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->setDefault(0);
         //   $table->addColumn('amount', 'decimal', '10,2')->setDefault(0);
            $table->addColumn('amount', 'varchar', 255)->nullable();

            $table->addColumn('transaction_id', 'int')->setDefault(0);
            $table->addColumn('external_id', 'varchar',150)->setDefault(0);
            $table->addColumn('created_at', 'int')->setDefault(0);
            $table->addPrimaryKey('req_id');
        };
        
        $tables['fs_escrow_transactions_record'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('trx_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->setDefault(0);
            $table->addColumn('Amount', 'decimal', '10,6')->setDefault(0);
            $table->addColumn('status', 'int')->setDefault(0);
            $table->addColumn('TxId', 'varchar',255)->nullable();
            $table->addColumn('created_at', 'int')->setDefault(0);
            $table->addPrimaryKey('trx_id');
        };

        $tables['fs_escrow_request_withdraw'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('req_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->setDefault(0);
            $table->addColumn('amount', 'float')->setDefault(0);
            $table->addColumn('address_from', 'text')->nullable();
            $table->addColumn('address_to', 'text')->nullable();
      //      $table->addColumn('otp', 'int')->setDefault(0);
            $table->addColumn('verfiy_at', 'int')->setDefault(0);
            $table->addColumn('is_proceed', 'int')->setDefault(0);
            $table->addColumn('transaction_id', 'int')->setDefault(0);
            $table->addColumn('request_state', 'enum')->values(['visible', 'moderated', 'deleted']);
            $table->addColumn('created_at', 'int')->setDefault(0);
        };
        
      

        $tables['fs_escrow'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('escrow_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->setDefault(0);
            $table->addColumn('to_user', 'int')->setDefault(0);
            // $table->addColumn('escrow_amount', 'int')->setDefault(0);
            $table->addColumn('escrow_amount', 'varchar', 255)->nullable();
            $table->addColumn('thread_id', 'int')->setDefault(0);
            $table->addColumn('transaction_id', 'int')->setDefault(0);
            $table->addColumn('escrow_status', 'int')->setDefault(0);
            $table->addColumn('admin_percentage', 'int')->setDefault(0);
            $table->addColumn('last_update', 'int')->setDefault(0);
            $table->addPrimaryKey('escrow_id');
        };

        return $tables;
    }
}