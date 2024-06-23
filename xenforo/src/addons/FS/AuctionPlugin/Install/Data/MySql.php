<?php

namespace FS\AuctionPlugin\Install\Data;

use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{

    public function getTables()
    {
        $tables = [];

        $tables['fs_auction_category'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('category_id', 'int')->autoIncrement();
            $table->addColumn('title', 'varchar', 100);
            $table->addColumn('description', 'text');
            $table->addColumn('parent_category_id', 'int')->setDefault(0);
            $table->addColumn('display_order', 'int')->setDefault(0);
            $table->addColumn('lft', 'int')->setDefault(0);
            $table->addColumn('rgt', 'int')->setDefault(0);
            $table->addColumn('depth', 'smallint', 5)->setDefault(0);
            $table->addColumn('breadcrumb_data', 'blob');
            $table->addColumn('auctions_count', 'int')->setDefault(0);
            $table->addColumn('layout_type', 'varchar', 20)->setDefault('list_view');
            $table->addKey(['parent_category_id', 'lft']);
            $table->addKey(['lft', 'rgt']);
            $table->addPrimaryKey('category_id');
        };

        $tables['fs_auction_listing'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('auction_id', 'int')->autoIncrement();
            $table->addColumn('category_id', 'int')->setDefault(0);
            $table->addColumn('thread_id', 'int')->setDefault(0);
            $table->addColumn('last_bumping', 'int')->setDefault(0);
            $table->addColumn('bumping_counts', 'int')->setDefault(0);

            $table->addPrimaryKey('auction_id');
        };

        $tables['fs_auction_bidding'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('bidding_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->setDefault(0);
            $table->addColumn('auction_id', 'int')->setDefault(0);
            $table->addColumn('created_at', 'int')->setDefault(0);
            $table->addColumn('bidding_amount', 'int')->setDefault(0);
            $table->addPrimaryKey('bidding_id');
        };

        return $tables;
    }

    public function getData()
    {
        $data = [];

        $data['fs_auction_category'] = "
            INSERT INTO 
                `fs_auction_category`
                (`category_id`, `title`, `description`, `parent_category_id`, `display_order`, `layout_type`,`lft`, `rgt`, `depth`, `breadcrumb_data`, `auctions_count`)
             VALUES
                (1, 'Example category', 'This is an example Auction category.', 0, 100, 'grid_view',3, 6, 0, '[]', 0);
        ";
        return $data;
    }
}
