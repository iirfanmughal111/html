<?php

namespace Z61\Classifieds\Install\Data;

use SV\Utils\InstallerHelper;
use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{
    use InstallerHelper;

    public function getTables()
    {
        $tables = [];

        $tables['xf_z61_classifieds_category'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'category_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'title', 'varchar', 100);
            $this->addOrChangeColumn($table, 'description', 'text');
            $this->addOrChangeColumn($table, 'parent_category_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'lft', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'rgt', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'depth', 'smallint', 5)->setDefault(0);
            $this->addOrChangeColumn($table, 'breadcrumb_data', 'blob');
            $this->addOrChangeColumn($table, 'listing_count', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'featured_count', 'smallint')->setDefault(0);
            $this->addOrChangeColumn($table, 'node_id', 'int')->nullable()->setDefault(null);
            $this->addOrChangeColumn($table, 'allow_paid', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'paid_feature_enable', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'paid_feature_days', 'int')->setDefault(30);
            $this->addOrChangeColumn($table, 'price', 'decimal', '10,2')->setDefault(0.00);
            $this->addOrChangeColumn($table, 'currency', 'varchar', 3)->setDefault('');
            $this->addOrChangeColumn($table, 'last_listing_id', 'int')->nullable();
            $this->addOrChangeColumn($table, 'last_listing_title', 'varchar', 100)->nullable();
            $this->addOrChangeColumn($table, 'last_listing_user_id', 'int')->nullable();
            $this->addOrChangeColumn($table, 'last_listing_username', 'varchar', 50)->nullable();
            $this->addOrChangeColumn($table, 'last_listing_date', 'int')->nullable();
            $this->addOrChangeColumn($table, 'last_listing_prefix_id', 'int')->nullable();
            $this->addOrChangeColumn($table, 'thread_prefix_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'field_cache', 'mediumblob');
            $this->addOrChangeColumn($table, 'prefix_cache', 'mediumblob');
            $this->addOrChangeColumn($table, 'moderate_listings', 'tinyint', 3)->setDefault(0);
            $this->addOrChangeColumn($table, 'payment_profile_ids', 'varbinary', 255)->setDefault('');
            $this->addOrChangeColumn($table, 'listing_type_ids', 'varbinary', 255)->setDefault('');
            $this->addOrChangeColumn($table, 'condition_ids', 'varbinary', 255)->setDefault('');
            $this->addOrChangeColumn($table, 'package_ids', 'varbinary', 255)->setDefault('');
            $this->addOrChangeColumn($table, 'contact_conversation', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'contact_email', 'tinyint', 3)->setDefault(0);
            $this->addOrChangeColumn($table, 'contact_custom', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'location_enable', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'require_listing_image', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'require_sold_user', 'tinyint')->setDefault(1);
            $this->addOrChangeColumn($table, 'replace_forum_action_button', 'tinyint')->setDefault(1);
            $this->addOrChangeColumn($table, 'exclude_expired', 'tinyint')->setDefault(1);
            $this->addOrChangeColumn($table, 'layout_type', 'varchar', 20)->setDefault('list_view');
            $this->addOrChangeColumn($table, 'phrase_listing_type', 'varchar', 60)->setDefault('z61_classifieds_type');
            $this->addOrChangeColumn($table, 'phrase_listing_condition', 'varchar', 60)->setDefault('z61_classifieds_condition');
            $this->addOrChangeColumn($table, 'phrase_listing_price', 'varchar', 60)->setDefault('price');
            $this->addOrChangeColumn($table,'listing_template', 'mediumtext');
            $table->addKey(['parent_category_id', 'lft']);
            $table->addUniqueKey('category_id');
            $table->addKey(['lft', 'rgt']);
        };

        $tables['xf_z61_classifieds_category_field'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
            $this->addOrChangeColumn($table, 'category_id', 'int');
            $table->addPrimaryKey(['field_id', 'category_id']);
            $table->addKey('category_id');
        };

        $tables['xf_z61_classifieds_listing_field'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
            $this->addOrChangeColumn($table, 'display_group', 'varchar', 25)->setDefault('above');
            $this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(1);
            $this->addOrChangeColumn($table, 'field_type', 'varbinary', 25)->setDefault('textbox');
            $this->addOrChangeColumn($table, 'field_choices', 'blob');
            $this->addOrChangeColumn($table, 'match_type', 'varbinary', 25)->setDefault('none');
            $this->addOrChangeColumn($table, 'match_params', 'blob');
            $this->addOrChangeColumn($table, 'max_length', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'required', 'tinyint')->setDefault(0);
            $this->addOrChangeColumn($table, 'display_template', 'text');
            $table->addPrimaryKey('field_id');
            $table->addKey(['display_group', 'display_order'], 'display_group_order');
        };

        $tables['xf_z61_classifieds_listing_field_value'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'listing_id', 'int');
            $this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
            $this->addOrChangeColumn($table, 'field_value', 'mediumtext');
            $table->addPrimaryKey(['listing_id', 'field_id']);
            $table->addKey('field_id');
        };

        $tables['xf_z61_classifieds_listing'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'listing_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'listing_type_id', 'int');
            $this->addOrChangeColumn($table, 'condition_id', 'int');
            $this->addOrChangeColumn($table, 'package_id', 'int');
            $this->addOrChangeColumn($table, 'category_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'title', 'varchar', 100);
            $this->addOrChangeColumn($table, 'content', 'mediumtext');
            $this->addOrChangeColumn($table, 'user_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'username', 'varchar', 50);
            $this->addOrChangeColumn($table, 'ip_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'discussion_thread_id', 'int')->comment('Points to an automatically-created thread for this listing');
            $this->addOrChangeColumn($table, 'reaction_score', 'int')->unsigned(false)->setDefault(0);
            $this->addOrChangeColumn($table, 'reactions', 'blob')->nullable();
            $this->addOrChangeColumn($table, 'reaction_users', 'blob');
            $this->addOrChangeColumn($table, 'view_count', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'price', 'decimal', '10,2')->setDefault(0.00);
            $this->addOrChangeColumn($table, 'currency', 'varchar', 3)->setDefault('');
            $this->addOrChangeColumn($table, 'listing_date', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'expiration_date' ,'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'last_edit_date', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'last_edit_user_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'listing_open', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'listing_state', 'enum')->values(['visible','moderated','deleted'])->setDefault('visible');
            $this->addOrChangeColumn($table, 'listing_status', 'enum')->values(['active','awaiting_payment','sold','expired'])->setDefault('active');
            $this->addOrChangeColumn($table, 'listing_location', 'varchar', 255)->setDefault('');
            $this->addOrChangeColumn($table, 'listing_location_data', 'mediumblob');
            $this->addOrChangeColumn($table, 'location_lat', 'decimal', 10)->nullable();
            $this->addOrChangeColumn($table, 'location_long', 'decimal', 10)->nullable()->unsigned(false);
            $this->addOrChangeColumn($table, 'warning_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'warning_message', 'varchar', 255)->setDefault('');
            $this->addOrChangeColumn($table, 'prefix_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'attach_count', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'custom_fields', 'mediumblob');
            $this->addOrChangeColumn($table, 'embed_metadata', 'blob')->nullable();
            $this->addOrChangeColumn($table, 'tags', 'mediumblob');
            $this->addOrChangeColumn($table, 'contact_email', 'varchar', 150);
            $this->addOrChangeColumn($table, 'contact_custom', 'varchar', 150);
            $this->addOrChangeColumn($table, 'contact_email_enable', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'contact_conversation_enable', 'tinyint', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'cover_image_id', 'int')->nullable();
            $this->addOrChangeColumn($table, 'sold_user_id', 'int')->nullable();
            $this->addOrChangeColumn($table, 'sold_username', 'varchar', 50)->nullable();
            $table->addKey('discussion_thread_id');
            $table->addKey('listing_date');
            $table->addKey('user_id');
        };

        $tables['xf_z61_classifieds_category_prefix'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'category_id', 'int');
            $this->addOrChangeColumn($table, 'prefix_id', 'int');
            $table->addPrimaryKey(['category_id', 'prefix_id']);
            $table->addKey('prefix_id');
        };

        $tables['xf_z61_classifieds_listing_prefix'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'prefix_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'prefix_group_id', 'int');
            $this->addOrChangeColumn($table, 'display_order', 'int');
            $this->addOrChangeColumn($table, 'materialized_order', 'int');
            $this->addOrChangeColumn($table, 'css_class', 'varchar', 50)->setDefault('');
            $this->addOrChangeColumn($table, 'allowed_user_group_ids', 'blob');
            $table->addKey('materialized_order');
        };

        $tables['xf_z61_classifieds_listing_prefix_group'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'prefix_group_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'display_order', 'int');
        };

        $tables['xf_z61_classifieds_listing_watch'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'user_id', 'int');
            $this->addOrChangeColumn($table, 'listing_id', 'int');
            $this->addOrChangeColumn($table, 'email_subscribe', 'tinyint')->setDefault(0);
            $table->addPrimaryKey(['user_id', 'listing_id']);
            $table->addKey(['listing_id', 'email_subscribe']);
        };

        $tables['xf_z61_classifieds_category_watch'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'user_id', 'int');
            $this->addOrChangeColumn($table, 'category_id', 'int');
            $this->addOrChangeColumn($table, 'notify_on', 'enum')->values(['','classifieds_listing']);
            $this->addOrChangeColumn($table, 'send_alert', 'tinyint');
            $this->addOrChangeColumn($table, 'send_email', 'tinyint');
            $this->addOrChangeColumn($table, 'include_children', 'tinyint');
            $table->addPrimaryKey(['user_id', 'category_id']);
            $table->addKey(['category_id', 'notify_on'], 'category_id_notify_on');
        };

        $tables['xf_z61_classifieds_listing_feature'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'listing_feature_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'listing_id', 'int');
            $this->addOrChangeColumn($table, 'user_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'date', 'int');
            $this->addOrChangeColumn($table, 'expiration_date', 'int')->nullable();
            $this->addOrChangeColumn($table, 'purchase_request_key', 'varbinary', 32)->nullable();
            $table->addUniqueKey(['user_id', 'listing_id'], 'user_id_listing_id');
            $table->addKey('user_id');
            $table->addKey('date');
        };

        $tables['xf_z61_classifieds_listing_feature_expire'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'listing_feature_id', 'int');
            $this->addOrChangeColumn($table, 'listing_id', 'int');
            $this->addOrChangeColumn($table, 'user_id', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'date', 'int');
            $this->addOrChangeColumn($table, 'expiration_date', 'int')->nullable();
            $this->addOrChangeColumn($table, 'purchase_request_key', 'varbinary', 32)->nullable();
            $table->addPrimaryKey('listing_id');
            $table->addUniqueKey(['user_id', 'listing_id'], 'user_id_listing_id');
            $table->addKey('date');
        };

        $tables['xf_z61_classifieds_listing_type'] = function ($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'listing_type_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'css_class', 'varchar', 100)->setDefault('label--green');
            $this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(100);
        };

        $tables['xf_z61_classifieds_listing_view'] = function($table)
        {
            /** @var Create|Alter $table */
            $table->engine('MEMORY');

            $this->addOrChangeColumn($table, 'listing_id', 'int');
            $this->addOrChangeColumn($table, 'total', 'int');
            $table->addPrimaryKey('listing_id');
        };

        $tables['xf_z61_classifieds_category_read'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'category_read_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'user_id', 'int');
            $this->addOrChangeColumn($table, 'category_id', 'int');
            $this->addOrChangeColumn($table, 'category_read_date', 'int');
            $table->addUniqueKey(['user_id', 'category_id']);
            $table->addKey('category_id');
            $table->addKey('category_read_date');
        };

        $tables['xf_z61_classifieds_listing_read'] = function($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'listing_read_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'user_id', 'int');
            $this->addOrChangeColumn($table, 'listing_id', 'int');
            $this->addOrChangeColumn($table, 'listing_read_date', 'int');
            $table->addUniqueKey(['user_id', 'listing_id']);
            $table->addKey('listing_id');
            $table->addKey('listing_read_date');
        };

        $tables['xf_z61_classifieds_condition'] = function ($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'condition_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'display_order', 'int');
            $this->addOrChangeColumn($table, 'active', 'tinyint', 3)->setDefault(1);
        };

        $tables['xf_z61_classifieds_package'] = function ($table)
        {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'package_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'display_order', 'int');
            $this->addOrChangeColumn($table, 'cost_amount', 'decimal', '10,2');
            $this->addOrChangeColumn($table, 'cost_currency', 'varchar', 3);
            $this->addOrChangeColumn($table, 'length_amount', 'tinyint', 3);
            $this->addOrChangeColumn($table, 'length_unit', 'enum')->values(['day','month','year',''])->setDefault('');
            $this->addOrChangeColumn($table, 'payment_profile_ids', 'varbinary', 255)->setDefault('');
            $this->addOrChangeColumn($table, 'active', 'tinyint', 3)->setDefault(1);
        };

        $tables['xf_z61_classifieds_feedback'] = function($table)
        {
            $this->addOrChangeColumn($table, 'feedback_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'from_user_id', 'int');
            $this->addOrChangeColumn($table, 'from_username', 'varchar', 50);
            $this->addOrChangeColumn($table, 'to_user_id', 'int');
            $this->addOrChangeColumn($table, 'to_username', 'varchar', 50);
            $this->addOrChangeColumn($table, 'listing_id', 'int')->nullable();
            $this->addOrChangeColumn($table, 'feedback', 'varchar', 80);
            $this->addOrChangeColumn($table, 'rating', 'enum')->values([
                'positive', 'neutral', 'negative'
            ]);
            $this->addOrChangeColumn($table, 'role', 'enum')->values([
                'buyer', 'seller', 'trader'
            ]);
            $this->addOrChangeColumn($table, 'feedback_date', 'int');
            $this->addOrChangeColumn($table, 'last_edit_date', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'last_edit_user_id', 'int')->setDefault(0);
        };

        $tables['xf_z61_classifieds_user_feedback'] =  function($table) {
            $this->addOrChangeColumn($table,'user_id', 'int');
            $this->addOrChangeColumn($table,'positive', 'int')->setDefault(0);
            $this->addOrChangeColumn($table,'neutral', 'int')->setDefault(0);
            $this->addOrChangeColumn($table,'negative', 'int')->setDefault(0);
            $this->addOrChangeColumn($table,'total', 'int')->unsigned(false)->setDefault(0);
            $this->addOrChangeColumn($table,'last_feedback_date', 'int');
        };
        return $tables;
    }

    public function getData()
    {
        $data = [];

        $data['xf_z61_classifieds_category'] = "
            INSERT INTO 
                `xf_z61_classifieds_category`
                (`category_id`, `title`, `description`, `parent_category_id`, `display_order`, `lft`, `rgt`, `depth`, `breadcrumb_data`, `listing_count`, `featured_count`, `node_id`, `allow_paid`, `paid_feature_enable`, `paid_feature_days`, `price`, `currency`, `last_listing_id`, `last_listing_title`, `last_listing_user_id`, `last_listing_username`, `last_listing_date`, `last_listing_prefix_id`, `thread_prefix_id`, `field_cache`, `prefix_cache`, `moderate_listings`, `payment_profile_ids`, `listing_type_ids`, `condition_ids`, `package_ids`, `contact_conversation`, `contact_email`, `contact_custom`, `location_enable`, `require_listing_image`, `require_sold_user`, `replace_forum_action_button`, `layout_type`, `exclude_expired`, `phrase_listing_type`, `phrase_listing_condition`, `phrase_listing_price`, `listing_template`)
             VALUES
                (1, 'Example category', 'This is an example Classifieds category.', 0, 100, 3, 6, 0, '[]', 0, 0, 0, 1, 0, 0, 1.00, 'USD', 0, '', 0, '', 0, 0, 0, '[]', '[]', 0, '', '1,2,3', '1', '1', 1, 0, 1, 1, 1, 0, 0, 'grid_view', 1, 'name', 'z61_classifieds_condition', 'price', '[B]Default listing template[/B]');

        ";

        $data['xf_z61_classifieds_listing_type'] = "
            INSERT INTO xf_z61_classifieds_listing_type
                (listing_type_id, display_order, css_class)
            VALUES
                (1, 10, 'label--green'),
                (2, 20, 'label--green'),
                (3, 30, 'label--green')
        ";

        $data['xf_z61_classifieds_package'] = "
            INSERT INTO xf_z61_classifieds_package
              (package_id, display_order, cost_amount, cost_currency, length_amount, length_unit, active)
            VALUES
              (1, 10, 0.00, 'usd', 30, 'day', 1),
              (2, 20, 10.00, 'usd', 30, 'day', 1) 
        ";

        $data['xf_z61_classifieds_condition'] = "
            INSERT INTO xf_z61_classifieds_condition
              (condition_id, display_order, active)          
            VALUES
            (1, 10, 1),
            (2, 20, 1),
            (3, 30, 1),
            (4, 40, 1),
            (5, 50, 1),
            (6, 60, 1)
        ";
        return $data;
    }

    public function getPhrases()
    {
        $phrases = [];

        $phrases['z61_listing_type_title.1'] = 'Want to buy';
        $phrases['z61_listing_type_title.2'] = 'Want to sell';
        $phrases['z61_listing_type_title.3'] = 'Want to trade';

        $phrases['z61_package_title.1'] = 'Free post';
        $phrases['z61_package_title.2'] = 'Paid post';

        $phrases['z61_condition_title.1'] = 'New other (see details)';
        $phrases['z61_condition_title.2'] = 'New';
        $phrases['z61_condition_title.3'] = 'Manufacturer refurbished';
        $phrases['z61_condition_title.4'] = 'Seller refurbished';
        $phrases['z61_condition_title.5'] = 'Used';
        $phrases['z61_condition_title.5'] = 'For parts or not working';

        return $phrases;
    }
}