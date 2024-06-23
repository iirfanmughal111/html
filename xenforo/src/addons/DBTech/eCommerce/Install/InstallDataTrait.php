<?php

namespace DBTech\eCommerce\Install;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

/**
 * @property \XF\AddOn\AddOn addOn
 * @property \XF\App app
 *
 * @method \XF\Db\AbstractAdapter db()
 * @method \XF\Db\SchemaManager schemaManager()
 * @method \XF\Db\Schema\Column addOrChangeColumn($table, $name, $type = null, $length = null)
 */
trait InstallDataTrait
{
	/**
	 * @return \Closure[]
	 */
	protected function getTables(): array
	{
		$tables = [];
		
		$tables['xf_dbtech_ecommerce_address'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'address_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'address_state', 'enum')->values(['visible', 'verified', 'moderated', 'deleted'])->setDefault('visible');
			$this->addOrChangeColumn($table, 'title', 'varchar', 100);
			$this->addOrChangeColumn($table, 'business_title', 'varchar', 255);
			$this->addOrChangeColumn($table, 'business_co', 'varchar', 100);
			$this->addOrChangeColumn($table, 'address1', 'varchar', 100);
			$this->addOrChangeColumn($table, 'address2', 'varchar', 100);
			$this->addOrChangeColumn($table, 'address3', 'varchar', 100);
			$this->addOrChangeColumn($table, 'address4', 'varchar', 100);
			$this->addOrChangeColumn($table, 'country_code', 'char', 2);
			$this->addOrChangeColumn($table, 'email', 'varchar', 120)->nullable(true);
			$this->addOrChangeColumn($table, 'sales_tax_id', 'varchar', 100);
			$this->addOrChangeColumn($table, 'is_default', 'tinyint', 1)->setDefault(0);
			$this->addOrChangeColumn($table, 'order_count', 'int')->setDefault(0);
			$table->addKey('user_id');
		};
		
		$tables['xf_dbtech_ecommerce_api_request_log'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'api_request_log_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'api_key', 'char', 32);
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'ip_address', 'varbinary', 16);
			$this->addOrChangeColumn($table, 'log_date', 'int');
			$this->addOrChangeColumn($table, 'request_uri', 'text');
			$this->addOrChangeColumn($table, 'referrer', 'text');
			$this->addOrChangeColumn($table, 'board_url', 'text');
			$this->addOrChangeColumn($table, 'http_host', 'text');
			$this->addOrChangeColumn($table, 'software', 'text');
			$this->addOrChangeColumn($table, 'software_version', 'text');
			$this->addOrChangeColumn($table, 'raw_data', 'mediumblob');
			$table->addKey('user_id');
		};
		
		$tables['xf_dbtech_ecommerce_category'] = function ($table)
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
			$this->addOrChangeColumn($table, 'product_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'last_update', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'last_product_title', 'varchar', 100)->setDefault('');
			$this->addOrChangeColumn($table, 'last_product_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'prefix_cache', 'mediumblob');
			$this->addOrChangeColumn($table, 'field_cache', 'mediumblob');
			$this->addOrChangeColumn($table, 'review_field_cache', 'mediumblob');
			$this->addOrChangeColumn($table, 'product_filters', 'blob');
			$this->addOrChangeColumn($table, 'require_prefix', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'thread_node_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'thread_prefix_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'product_update_notify', 'enum')->values(['thread', 'reply'])->setDefault('thread');
			$this->addOrChangeColumn($table, 'always_moderate_create', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'always_moderate_update', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'min_tags', 'smallint', 5)->setDefault(0);
			$table->addKey(['parent_category_id', 'lft']);
			$table->addKey(['lft', 'rgt']);
		};
		
		$tables['xf_dbtech_ecommerce_category_field'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'category_id', 'int');
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$table->addPrimaryKey(['category_id', 'field_id']);
			$table->addKey('field_id');
		};
		
		$tables['xf_dbtech_ecommerce_category_prefix'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'category_id', 'int');
			$this->addOrChangeColumn($table, 'prefix_id', 'int');
			$table->addPrimaryKey(['category_id', 'prefix_id']);
			$table->addKey('prefix_id');
		};

		$tables['xf_dbtech_ecommerce_category_review_field'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'category_id', 'int');
			$table->addPrimaryKey(['field_id', 'category_id']);
			$table->addKey('category_id');
		};
		
		$tables['xf_dbtech_ecommerce_category_watch'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'category_id', 'int');
			$this->addOrChangeColumn($table, 'notify_on', 'enum')->values(['', 'product', 'download']);
			$this->addOrChangeColumn($table, 'send_alert', 'tinyint', 3);
			$this->addOrChangeColumn($table, 'send_email', 'tinyint', 3);
			$this->addOrChangeColumn($table, 'include_children', 'tinyint', 3);
			$table->addPrimaryKey(['user_id', 'category_id']);
			$table->addKey(['category_id', 'notify_on'], 'category_id_notify_on');
		};
		
		$tables['xf_dbtech_ecommerce_commission'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'commission_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'user_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'name', 'varchar', 100);
			$this->addOrChangeColumn($table, 'email', 'varchar', 120)->nullable(true);
			$this->addOrChangeColumn($table, 'last_paid_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'total_payments', 'decimal', '10,2')->setDefault('0.00');
			$this->addOrChangeColumn($table, 'product_commissions', 'mediumblob');
			$table->addKey('user_id');
			$table->addKey('name');
		};
		
		$tables['xf_dbtech_ecommerce_commission_payment'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'commission_payment_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'commission_id', 'int');
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'ip_id', 'int');
			$this->addOrChangeColumn($table, 'payment_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'payment_amount', 'decimal', '10,2')->unsigned(false)->setDefault('0.00');
			$this->addOrChangeColumn($table, 'message', 'mediumtext');
			$table->addKey('commission_id');
			$table->addKey('user_id');
			$table->addKey('ip_id');
		};
		
		$tables['xf_dbtech_ecommerce_country'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'country_code', 'char', 2);
			$this->addOrChangeColumn($table, 'name', 'varchar', 255);
			$this->addOrChangeColumn($table, 'native_name', 'varchar', 255);
			$this->addOrChangeColumn($table, 'iso_code', 'char', 3);
			$this->addOrChangeColumn($table, 'sales_tax_rate', 'decimal', '10,3')->unsigned(false)->setDefault('-1.000');
			$table->addPrimaryKey('country_code');
			$table->addKey('sales_tax_rate');
		};
		
		$tables['xf_dbtech_ecommerce_country_shipping_zone_map'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'shipping_zone_id', 'int');
			$this->addOrChangeColumn($table, 'country_code', 'char', 2);
			$table->addPrimaryKey(['shipping_zone_id', 'country_code']);
			$table->addKey('shipping_zone_id');
			$table->addKey('country_code');
		};
		
		$tables['xf_dbtech_ecommerce_coupon'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'coupon_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'coupon_state', 'enum')->values(['visible', 'deleted'])->setDefault('visible');
			$this->addOrChangeColumn($table, 'coupon_code', 'varchar', 25);
			$this->addOrChangeColumn($table, 'coupon_type', 'enum')->values(['percent', 'value'])->setDefault('percent');
			$this->addOrChangeColumn($table, 'coupon_percent', 'decimal', '5,2')->setDefault('0.00');
			$this->addOrChangeColumn($table, 'coupon_value', 'decimal', '10,2')->setDefault('0.00');
			$this->addOrChangeColumn($table, 'discount_excluded', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'allow_auto_discount', 'tinyint', 3)->setDefault(1);
			$this->addOrChangeColumn($table, 'start_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'expiry_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'remaining_uses', 'int', 10)->unsigned(false)->setDefault(-1);
			$this->addOrChangeColumn($table, 'minimum_products', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'maximum_products', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'minimum_cart_value', 'decimal', '10,2')->setDefault('0.00');
			$this->addOrChangeColumn($table, 'maximum_cart_value', 'decimal', '10,2')->setDefault('0.00');
			$this->addOrChangeColumn($table, 'product_discounts', 'mediumblob');
			$table->addKey('coupon_code');
		};
		
		$tables['xf_dbtech_ecommerce_coupon_log'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'coupon_log_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'order_id', 'int');
			$this->addOrChangeColumn($table, 'order_item_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'coupon_id', 'int');
			$this->addOrChangeColumn($table, 'coupon_discounts', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'currency', 'char', 3);
			$this->addOrChangeColumn($table, 'log_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'ip_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'log_details', 'mediumblob')->nullable(true);
			$table->addKey(['user_id', 'log_date'], 'user_id_log_date');
			$table->addKey('log_date');
			$table->addKey('product_id');
			$table->addKey('coupon_id');
		};
		
		$tables['xf_dbtech_ecommerce_discount'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'discount_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'discount_state', 'enum')->values(['visible', 'deleted'])->setDefault('visible');
			$this->addOrChangeColumn($table, 'discount_threshold', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'discount_percent', 'decimal', '5,2');
		};
		
		$tables['xf_dbtech_ecommerce_distributor'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'license_length_amount', 'tinyint', 3);
			$this->addOrChangeColumn($table, 'license_length_unit', 'enum')->values(['day', 'month', 'year', '']);
			$this->addOrChangeColumn($table, 'available_products', 'mediumblob');
			$table->addPrimaryKey('user_id');
		};
		
		$tables['xf_dbtech_ecommerce_distributor_log'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'distributor_log_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'distributor_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'license_id', 'int');
			$this->addOrChangeColumn($table, 'log_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'ip_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'log_details', 'mediumblob')->nullable(true);
			$table->addKey(['distributor_id', 'log_date'], 'distributor_id_log_date');
			$table->addKey(['user_id', 'log_date'], 'user_id_log_date');
			$table->addKey('log_date');
			$table->addKey('product_id');
			$table->addKey('license_id');
		};
		
		$tables['xf_dbtech_ecommerce_download'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'download_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'download_state', 'enum')->values(['visible', 'scheduled', 'moderated', 'deleted'])->setDefault('visible');
			$this->addOrChangeColumn($table, 'version_string', 'varchar', 25);
			$this->addOrChangeColumn($table, 'release_date', 'int');
			$this->addOrChangeColumn($table, 'change_log', 'mediumblob');
			$this->addOrChangeColumn($table, 'has_new_features', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'has_changed_features', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'has_bug_fixes', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'is_unstable', 'tinyint')->setDefault(0);
			$this->addOrChangeColumn($table, 'release_notes', 'mediumblob');
			$this->addOrChangeColumn($table, 'discussion_thread_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'download_type', 'varchar', 25)->setDefault('dbtech_ecommerce_attach');
			$this->addOrChangeColumn($table, 'download_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'full_download_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'attach_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'reaction_score', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'reactions', 'blob');
			$this->addOrChangeColumn($table, 'reaction_users', 'blob');
			$this->addOrChangeColumn($table, 'warning_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'warning_message', 'varchar', 255)->setDefault('');
			$this->addOrChangeColumn($table, 'embed_metadata', 'blob')->nullable(true);
			$table->addKey('product_id');
			$table->addKey('user_id');
		};
		
		$tables['xf_dbtech_ecommerce_download_log'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'download_log_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'license_id', 'int');
			$this->addOrChangeColumn($table, 'download_id', 'int');
			$this->addOrChangeColumn($table, 'product_version', 'varchar', 25);
			$this->addOrChangeColumn($table, 'log_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'ip_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'license_fields', 'mediumtext')->nullable(true);
			$this->addOrChangeColumn($table, 'log_details', 'mediumblob')->nullable(true);
			$table->addKey(['user_id', 'log_date'], 'user_id_log_date');
			$table->addKey('log_date');
			$table->addKey('product_id');
			$table->addKey('license_id');
			$table->addKey('download_id');
		};
		
		$tables['xf_dbtech_ecommerce_download_log_field_value'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'download_log_id', 'int');
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'field_value', 'mediumtext');
			$table->addPrimaryKey(['download_log_id', 'field_id']);
			$table->addKey('field_id');
		};
		
		$tables['xf_dbtech_ecommerce_download_version'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'download_version_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'download_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'product_version', 'varchar', 25);
			$this->addOrChangeColumn($table, 'product_version_type', 'enum')->values(['full', 'demo'])->setDefault('full');
			$this->addOrChangeColumn($table, 'directories', 'text');
			$this->addOrChangeColumn($table, 'attach_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'download_url', 'varchar', 250)->setDefault('');
			$table->addUniqueKey(['download_id', 'product_id', 'product_version', 'product_version_type']);
			$table->addKey('product_id');
		};
		
		$tables['xf_dbtech_ecommerce_income_stats_daily'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'stats_date', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int')->unsigned(false)->setDefault(-1);
			$this->addOrChangeColumn($table, 'counter', 'decimal', '10,2')->unsigned(false);
			$table->addPrimaryKey(['stats_date', 'product_id']);
		};
		
		$tables['xf_dbtech_ecommerce_license'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'license_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'parent_license_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'username', 'varchar', 50);
			$this->addOrChangeColumn($table, 'purchase_date', 'int');
			$this->addOrChangeColumn($table, 'order_id', 'int');
			$this->addOrChangeColumn($table, 'purchase_request_key', 'varbinary', 32)->nullable();
			$this->addOrChangeColumn($table, 'expiry_date', 'int');
			$this->addOrChangeColumn($table, 'sent_expiring_reminder', 'tinyint')->setDefault(0);
			$this->addOrChangeColumn($table, 'sent_expired_reminder', 'tinyint')->setDefault(0);
			$this->addOrChangeColumn($table, 'latest_download_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'license_key', 'varchar', 250);
			$this->addOrChangeColumn($table, 'license_state', 'enum')->values(['visible', 'awaiting_payment', 'moderated', 'deleted'])->setDefault('visible');
			$this->addOrChangeColumn($table, 'license_fields', 'mediumtext');
			$this->addOrChangeColumn($table, 'discussion_thread_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'required_user_group_ids', 'blob')->nullable(true);
		};
		
		$tables['xf_dbtech_ecommerce_license_field'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'display_group', 'varchar', 25)->setDefault('list');
			$this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(1);
			$this->addOrChangeColumn($table, 'field_type', 'varbinary', 25)->setDefault('textbox');
			$this->addOrChangeColumn($table, 'field_choices', 'blob');
			$this->addOrChangeColumn($table, 'match_type', 'varbinary', 25)->setDefault('none');
			$this->addOrChangeColumn($table, 'match_params', 'blob');
			$this->addOrChangeColumn($table, 'max_length', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'required', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'user_editable', 'enum')->values(['yes', 'once', 'never'])->setDefault('yes');
			$this->addOrChangeColumn($table, 'moderator_editable', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'display_template', 'text');
			$table->addPrimaryKey('field_id');
			$table->addKey(['display_group', 'display_order'], 'display_group_order');
		};
		
		$tables['xf_dbtech_ecommerce_license_field_value'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'license_id', 'int');
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'field_value', 'mediumtext');
			$table->addPrimaryKey(['license_id', 'field_id']);
			$table->addKey('field_id');
		};
		
		$tables['xf_dbtech_ecommerce_order'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'order_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'ip_address', 'varbinary', 16);
			$this->addOrChangeColumn($table, 'order_date', 'int');
			$this->addOrChangeColumn($table, 'completed_date', 'int');
			$this->addOrChangeColumn($table, 'reversed_date', 'int');
			$this->addOrChangeColumn($table, 'order_state', 'varchar', 25);
			$this->addOrChangeColumn($table, 'purchase_request_key', 'varbinary', 32)->nullable();
			$this->addOrChangeColumn($table, 'address_id', 'int');
			$this->addOrChangeColumn($table, 'shipping_address_id', 'int');
			$this->addOrChangeColumn($table, 'store_credit_amount', 'int');
			$this->addOrChangeColumn($table, 'coupon_id', 'int');
			$this->addOrChangeColumn($table, 'sub_total', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'sale_discounts', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'coupon_discounts', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'automatic_discounts', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'shipping_cost', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'sales_tax', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'taxable_order_total', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'currency', 'char', '3');
			$this->addOrChangeColumn($table, 'cost_amount', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'has_invoice', 'tinyint');
			$this->addOrChangeColumn($table, 'sent_reminder', 'tinyint');
			$this->addOrChangeColumn($table, 'extra_data', 'mediumblob')->nullable(true);
			$table->addKey(['user_id', 'order_date'], 'user_id_order_date');
			$table->addKey('order_state');
			$table->addKey('address_id');
			$table->addKey('shipping_address_id');
			$table->addKey('coupon_id');
		};
		
		$tables['xf_dbtech_ecommerce_order_field'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'display_group', 'varchar', 25)->setDefault('above_terms');
			$this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(1);
			$this->addOrChangeColumn($table, 'field_type', 'varbinary', 25)->setDefault('textbox');
			$this->addOrChangeColumn($table, 'field_choices', 'blob');
			$this->addOrChangeColumn($table, 'match_type', 'varbinary', 25)->setDefault('none');
			$this->addOrChangeColumn($table, 'match_params', 'blob');
			$this->addOrChangeColumn($table, 'max_length', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'required', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'user_editable', 'enum')->values(['yes', 'once', 'never'])->setDefault('yes');
			$this->addOrChangeColumn($table, 'moderator_editable', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'display_template', 'text');
			$table->addPrimaryKey('field_id');
			$table->addKey(['display_group', 'display_order'], 'display_group_order');
		};
		
		$tables['xf_dbtech_ecommerce_order_field_map'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$table->addPrimaryKey(['product_id', 'field_id']);
			$table->addKey('field_id');
		};
		
		$tables['xf_dbtech_ecommerce_order_field_value'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'order_item_id', 'int');
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'field_value', 'mediumtext');
			$table->addPrimaryKey(['order_item_id', 'field_id']);
			$table->addKey('field_id');
		};
		
		$tables['xf_dbtech_ecommerce_order_item'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'order_item_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'order_id', 'int');
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'product_cost_id', 'int');
			$this->addOrChangeColumn($table, 'parent_order_item_id', 'int');
			$this->addOrChangeColumn($table, 'license_id', 'int');
			$this->addOrChangeColumn($table, 'parent_license_id', 'int');
			$this->addOrChangeColumn($table, 'coupon_id', 'int');
			$this->addOrChangeColumn($table, 'shipping_method_id', 'int');
			$this->addOrChangeColumn($table, 'item_type', 'enum')->values(['new', 'upgrade', 'renew']);
			$this->addOrChangeColumn($table, 'product_fields', 'mediumblob');
			$this->addOrChangeColumn($table, 'quantity', 'int')->setDefault(1);
			$this->addOrChangeColumn($table, 'base_price', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'sale_discount', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'coupon_discount', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'shipping_cost', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'taxable_price', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'sales_tax', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'price', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'currency', 'char', '3');
			$this->addOrChangeColumn($table, 'extra_data', 'mediumblob')->nullable(true);
			$this->addOrChangeColumn($table, 'discussion_thread_id', 'int')->setDefault(0);
			$table->addKey(['user_id', 'order_id'], 'user_id_order_id');
			$table->addKey('product_id');
			$table->addKey('product_cost_id');
			$table->addKey('license_id');
			$table->addKey('parent_license_id');
			$table->addKey('coupon_id');
		};
		
		$tables['xf_dbtech_ecommerce_product'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'title', 'varchar', 100);
			$this->addOrChangeColumn($table, 'parent_product_id', 'int');
			$this->addOrChangeColumn($table, 'product_category_id', 'int');
			$this->addOrChangeColumn($table, 'product_state', 'enum')->values(['visible', 'moderated', 'deleted'])->setDefault('visible');
			$this->addOrChangeColumn($table, 'creation_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'last_update', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'latest_version_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'is_paid', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'is_featured', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'is_discountable', 'tinyint', 3)->setDefault(1);
			$this->addOrChangeColumn($table, 'is_listed', 'tinyint', 3)->setDefault(1);
			$this->addOrChangeColumn($table, 'welcome_email', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'is_all_access', 'tinyint', 3)->setDefault(1);
			$this->addOrChangeColumn($table, 'all_access_group_ids', 'blob')->nullable(true);
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'username', 'varchar', 50);
			$this->addOrChangeColumn($table, 'ip_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'warning_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'warning_message', 'varchar', 255)->setDefault('');
			$this->addOrChangeColumn($table, 'requirements', 'mediumblob');
			$this->addOrChangeColumn($table, 'description_full', 'mediumblob');
			$this->addOrChangeColumn($table, 'product_specification', 'mediumblob');
			$this->addOrChangeColumn($table, 'copyright_info', 'mediumblob');
			$this->addOrChangeColumn($table, 'attach_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'reaction_score', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'reactions', 'blob');
			$this->addOrChangeColumn($table, 'reaction_users', 'blob');
			$this->addOrChangeColumn($table, 'product_type', 'varchar', 50)->setDefault('dbtech_ecommerce_digital');
			$this->addOrChangeColumn($table, 'product_type_data', 'mediumblob');
			$this->addOrChangeColumn($table, 'license_prefix', 'varchar', 75);
			$this->addOrChangeColumn($table, 'product_versions', 'blob');
			$this->addOrChangeColumn($table, 'has_demo', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'extra_group_ids', 'varbinary', 255)->setDefault('');
			$this->addOrChangeColumn($table, 'temporary_extra_group_ids', 'varbinary', 255)->setDefault('');
			$this->addOrChangeColumn($table, 'support_node_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'thread_node_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'thread_prefix_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'discussion_thread_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'field_cache', 'mediumblob');
			$this->addOrChangeColumn($table, 'product_fields', 'mediumblob');
			$this->addOrChangeColumn($table, 'product_filters', 'blob');
			$this->addOrChangeColumn($table, 'cost_cache', 'mediumblob');
			$this->addOrChangeColumn($table, 'shipping_zones', 'blob');
			$this->addOrChangeColumn($table, 'download_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'full_download_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'rating_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'rating_sum', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'rating_avg', 'float', '')->setDefault(0);
			$this->addOrChangeColumn($table, 'rating_weighted', 'float', '')->setDefault(0);
			$this->addOrChangeColumn($table, 'release_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'review_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'license_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'purchase_count', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'icon_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'icon_extension', 'varchar', 5)->setDefault('jpg');
			$this->addOrChangeColumn($table, 'prefix_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'tags', 'mediumblob');
			$this->addOrChangeColumn($table, 'global_branding_free', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'branding_free', 'int')->setDefault(0);
			$table->addKey(['product_category_id', 'last_update'], 'category_last_update');
			$table->addKey(['product_category_id', 'rating_weighted'], 'category_rating_weighted');
			$table->addKey('last_update');
			$table->addKey('rating_weighted');
			$table->addKey(['user_id', 'last_update']);
			$table->addKey('discussion_thread_id');
			$table->addKey('prefix_id');
		};
		
		$tables['xf_dbtech_ecommerce_product_commission_value'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'commission_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'commission_type', 'enum')->values(['percent', 'value']);
			$this->addOrChangeColumn($table, 'commission_value', 'decimal', '10,2')->setDefault('0.00');
			$table->addPrimaryKey(['commission_id', 'product_id']);
		};
		
		$tables['xf_dbtech_ecommerce_product_cost'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_cost_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'product_type', 'varchar', 50)->setDefault('dbtech_ecommerce_digital');
			$this->addOrChangeColumn($table, 'title', 'varchar', 100);
			$this->addOrChangeColumn($table, 'description', 'varchar', 255)->setDefault('');
			$this->addOrChangeColumn($table, 'creation_date', 'int');
			$this->addOrChangeColumn($table, 'cost_amount', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'renewal_type', 'enum')->values(['global', 'fixed', 'percentage'])->setDefault('global');
			$this->addOrChangeColumn($table, 'renewal_amount', 'decimal', '10,2')->nullable(true);
			$this->addOrChangeColumn($table, 'highlighted', 'tinyint');
			$this->addOrChangeColumn($table, 'stock', 'int');
			$this->addOrChangeColumn($table, 'weight', 'decimal', '10,2')->setDefault('0.00');
			$this->addOrChangeColumn($table, 'length_amount', 'tinyint', 3);
			$this->addOrChangeColumn($table, 'length_unit', 'enum')->values(['day', 'month', 'year', '']);
		};
		
		$tables['xf_dbtech_ecommerce_product_coupon_value'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'coupon_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'product_value', 'decimal', '10,2')->setDefault('0.00');
			$table->addPrimaryKey(['coupon_id', 'product_id']);
		};

		$tables['xf_dbtech_ecommerce_product_distributor_value'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'available_licenses', 'int')->unsigned(false)->setDefault(-1);
			$table->addPrimaryKey(['user_id', 'product_id']);
		};
		
		$tables['xf_dbtech_ecommerce_product_download'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_download_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'download_id', 'int');
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'last_download_date', 'int');
			$table->addUniqueKey(['download_id', 'user_id'], 'download_user');
			$table->addKey(['user_id', 'product_id'], 'user_product');
		};
		
		$tables['xf_dbtech_ecommerce_product_feature_temp'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_feature_temp_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'feature_key', 'varbinary', 50)->nullable();
			$this->addOrChangeColumn($table, 'create_date', 'int')->nullable();
			$this->addOrChangeColumn($table, 'expiry_date', 'int')->nullable();
			$table->addUniqueKey(['product_id', 'feature_key'], 'product_id');
			$table->addKey('feature_key');
			$table->addKey('expiry_date');
		};
		
		$tables['xf_dbtech_ecommerce_product_field'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'display_group', 'varchar', 25)->setDefault('above_info');
			$this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(1);
			$this->addOrChangeColumn($table, 'field_type', 'varbinary', 25)->setDefault('textbox');
			$this->addOrChangeColumn($table, 'field_choices', 'blob');
			$this->addOrChangeColumn($table, 'match_type', 'varbinary', 25)->setDefault('none');
			$this->addOrChangeColumn($table, 'match_params', 'blob');
			$this->addOrChangeColumn($table, 'max_length', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'required', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'user_editable', 'enum')->values(['yes', 'once', 'never'])->setDefault('yes');
			$this->addOrChangeColumn($table, 'moderator_editable', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'filterable', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'display_template', 'text');
			$table->addPrimaryKey('field_id');
			$table->addKey(['display_group', 'display_order'], 'display_group_order');
		};
		
		$tables['xf_dbtech_ecommerce_product_field_value'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'field_value', 'mediumtext');
			$table->addPrimaryKey(['product_id', 'field_id']);
			$table->addKey('field_id');
		};
		
		$tables['xf_dbtech_ecommerce_product_filter_map'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'filter_id', 'varbinary', 25);
			$table->addPrimaryKey(['product_id', 'filter_id']);
			$table->addKey('filter_id');
		};
		
		$tables['xf_dbtech_ecommerce_product_prefix'] = function ($table)
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
		
		$tables['xf_dbtech_ecommerce_product_prefix_group'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'prefix_group_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'display_order', 'int');
		};
		
		$tables['xf_dbtech_ecommerce_product_rating'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_rating_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'rating', 'tinyint', 3);
			$this->addOrChangeColumn($table, 'rating_date', 'int');
			$this->addOrChangeColumn($table, 'message', 'mediumtext');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'version_string', 'varchar', 50);
			$this->addOrChangeColumn($table, 'author_response', 'mediumtext');
			$this->addOrChangeColumn($table, 'is_review', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'count_rating', 'tinyint', 3)->setDefault(1);
			$this->addOrChangeColumn($table, 'rating_state', 'enum')->values(['visible','deleted'])->setDefault('visible');
			$this->addOrChangeColumn($table, 'warning_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'is_anonymous', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'custom_fields', 'mediumblob');
			$this->addOrChangeColumn($table, 'vote_score', 'int')->unsigned(false);
			$this->addOrChangeColumn($table, 'vote_count', 'int')->setDefault(0);
			$table->addUniqueKey(['product_id', 'user_id'], 'product_user_id');
			$table->addKey('user_id');
			$table->addKey(['count_rating', 'product_id']);
			$table->addKey(['product_id', 'rating_date']);
			$table->addKey('rating_date');
		};

		$tables['xf_dbtech_ecommerce_product_review_field'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'display_group', 'varchar', 25)->setDefault('above_info');
			$this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(1);
			$this->addOrChangeColumn($table, 'field_type', 'varbinary', 25)->setDefault('textbox');
			$this->addOrChangeColumn($table, 'field_choices', 'blob');
			$this->addOrChangeColumn($table, 'match_type', 'varbinary', 25)->setDefault('none');
			$this->addOrChangeColumn($table, 'match_params', 'blob');
			$this->addOrChangeColumn($table, 'max_length', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'required', 'tinyint')->setDefault(0);
			$this->addOrChangeColumn($table, 'display_template', 'text');
			$this->addOrChangeColumn($table, 'wrapper_template', 'text');
			$table->addPrimaryKey('field_id');
			$table->addKey(['display_group', 'display_order'], 'display_group_order');
		};

		$tables['xf_dbtech_ecommerce_product_review_field_value'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_rating_id', 'int');
			$this->addOrChangeColumn($table, 'field_id', 'varbinary', 25);
			$this->addOrChangeColumn($table, 'field_value', 'mediumtext');
			$table->addPrimaryKey(['product_rating_id', 'field_id']);
			$table->addKey('field_id');
		};
		
		$tables['xf_dbtech_ecommerce_product_sale'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'sale_type', 'enum')->values(['percent', 'value'])->setDefault('percent');
			$this->addOrChangeColumn($table, 'sale_percent', 'decimal', '5,2')->setDefault('0.00');
			$this->addOrChangeColumn($table, 'sale_value', 'decimal', '10,2');
			$table->addPrimaryKey('product_id');
		};
		
		$tables['xf_dbtech_ecommerce_product_watch'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'email_subscribe', 'tinyint', 3)->setDefault(0);
			$table->addPrimaryKey(['user_id', 'product_id']);
			$table->addKey(['product_id', 'email_subscribe']);
		};

		$tables['xf_dbtech_ecommerce_product_welcome_email'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'product_id', 'int')->primaryKey();
			$this->addOrChangeColumn($table, 'email_format', 'enum')->values(['text', 'html'])->setDefault('text');
			$this->addOrChangeColumn($table, 'email_title', 'varchar', 250)->setDefault('');
			$this->addOrChangeColumn($table, 'email_body', 'mediumblob')->nullable(true);
			$this->addOrChangeColumn($table, 'from_name', 'varchar', 250)->setDefault('');
			$this->addOrChangeColumn($table, 'from_email', 'varchar', 120)->setDefault('');
		};
		
		$tables['xf_dbtech_ecommerce_purchase_log'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'purchase_log_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'order_id', 'int');
			$this->addOrChangeColumn($table, 'order_item_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'license_id', 'int');
			$this->addOrChangeColumn($table, 'quantity', 'int')->setDefault(1);
			$this->addOrChangeColumn($table, 'log_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'ip_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'cost_amount', 'decimal', '10,2')->unsigned(false);
			$this->addOrChangeColumn($table, 'currency', 'char', '3')->after('cost_amount');
			$this->addOrChangeColumn($table, 'log_type', 'enum')->values(['new', 'upgrade', 'renew', 'reversal', 'refunded'])->setDefault('new');
			$this->addOrChangeColumn($table, 'log_details', 'mediumblob')->nullable(true);
			$table->addKey(['user_id', 'log_date'], 'user_id_log_date');
			$table->addKey('log_date');
			$table->addKey('product_id');
			$table->addKey('license_id');
		};
		
		$tables['xf_dbtech_ecommerce_sale'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'sale_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'title', 'varchar', 100);
			$this->addOrChangeColumn($table, 'sale_state', 'enum')->values(['visible', 'moderated', 'deleted'])->setDefault('visible');
			$this->addOrChangeColumn($table, 'email_notify', 'tinyint', 3)->setDefault(1);
			$this->addOrChangeColumn($table, 'sale_type', 'enum')->values(['percent', 'value'])->setDefault('percent');
			$this->addOrChangeColumn($table, 'sale_percent', 'decimal', '5,2')->setDefault('0.00');
			$this->addOrChangeColumn($table, 'sale_value', 'decimal', '10,2');
			$this->addOrChangeColumn($table, 'discount_excluded', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'allow_auto_discount', 'tinyint', 3)->setDefault(1);
			$this->addOrChangeColumn($table, 'feature_products', 'tinyint', 3)->setDefault(1);
			$this->addOrChangeColumn($table, 'is_recurring', 'tinyint', 3)->setDefault(0);
			$this->addOrChangeColumn($table, 'recurring_length_amount', 'tinyint', 3);
			$this->addOrChangeColumn($table, 'recurring_length_unit', 'enum')->values(['day', 'month', 'year', '']);
			$this->addOrChangeColumn($table, 'start_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'end_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'other_dates', 'mediumblob');
			$this->addOrChangeColumn($table, 'product_discounts', 'mediumblob');
			$this->addOrChangeColumn($table, 'thread_node_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'thread_prefix_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'discussion_thread_id', 'int')->setDefault(0);
		};

		$tables['xf_dbtech_ecommerce_serial_key'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'serial_key_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$this->addOrChangeColumn($table, 'license_id', 'int');
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'serial_key', 'varchar', 100);
			$this->addOrChangeColumn($table, 'serial_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'available', 'tinyint')->setDefault(1);
			$table->addKey('product_id');
			$table->addKey('license_id');
			$table->addKey('user_id');
			$table->addUniqueKey('serial_key');
		};
		
		$tables['xf_dbtech_ecommerce_shipping_combination'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'shipping_method_id', 'int');
			$this->addOrChangeColumn($table, 'shipping_zone_id', 'int');
			$this->addOrChangeColumn($table, 'country_code', 'char', 2);
			$this->addOrChangeColumn($table, 'cost_formula', 'text');
			
			$table->addPrimaryKey(['shipping_method_id', 'shipping_zone_id', 'country_code']);
		};
		
		$tables['xf_dbtech_ecommerce_shipping_method'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'shipping_method_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'title', 'varchar', 100);
			$this->addOrChangeColumn($table, 'active', 'tinyint', 3);
			$this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'cost_formula', 'text');
		};
		
		$tables['xf_dbtech_ecommerce_shipping_method_shipping_zone_map'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'shipping_method_id', 'int');
			$this->addOrChangeColumn($table, 'shipping_zone_id', 'int');
			$table->addPrimaryKey(['shipping_method_id', 'shipping_zone_id']);
			$table->addKey('shipping_method_id');
			$table->addKey('shipping_zone_id');
		};
		
		$tables['xf_dbtech_ecommerce_shipping_zone'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'shipping_zone_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'title', 'varchar', 100);
			$this->addOrChangeColumn($table, 'active', 'tinyint', 3);
			$this->addOrChangeColumn($table, 'display_order', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'countries', 'blob');
			$this->addOrChangeColumn($table, 'shipping_methods', 'blob');
		};
		
		$tables['xf_dbtech_ecommerce_shipping_zone_product_map'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'shipping_zone_id', 'int');
			$this->addOrChangeColumn($table, 'product_id', 'int');
			$table->addPrimaryKey(['shipping_zone_id', 'product_id']);
			$table->addKey('shipping_zone_id');
			$table->addKey('product_id');
		};
		
		$tables['xf_dbtech_ecommerce_store_credit_log'] = function ($table)
		{
			/** @var Create|Alter $table */
			$this->addOrChangeColumn($table, 'store_credit_log_id', 'int')->autoIncrement();
			$this->addOrChangeColumn($table, 'order_id', 'int');
			$this->addOrChangeColumn($table, 'store_credit_amount', 'int')->unsigned(false);
			$this->addOrChangeColumn($table, 'log_date', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'user_id', 'int');
			$this->addOrChangeColumn($table, 'ip_id', 'int')->setDefault(0);
			$this->addOrChangeColumn($table, 'log_details', 'mediumblob')->nullable(true);
			$table->addKey(['user_id', 'log_date'], 'user_id_log_date');
			$table->addKey('log_date');
			$table->addKey('order_id');
		};
		
		return $tables;
	}
	
	protected function getAlterDefinitions(): array
	{
		$definitions = [];
		
		$definitions['xf_user'] = [
			'columns' => [
				'dbtech_ecommerce_store_credit'   => [
					'type'    => 'int',
					'length'  => null,
					'default' => 0
				],
				'dbtech_ecommerce_tos_accept'     => [
					'type'    => 'int',
					'length'  => null,
					'default' => 0
				],
				'dbtech_ecommerce_product_count'  => [
					'type'    => 'int',
					'length'  => null,
					'default' => 0
				],
				'dbtech_ecommerce_license_count'  => [
					'type'    => 'int',
					'length'  => null,
					'default' => 0
				],
				'dbtech_ecommerce_amount_spent'   => [
					'type'    => 'decimal',
					'length'  => '10,2',
					'default' => '0.00'
				],
				'dbtech_ecommerce_cart_items'     => [
					'type'    => 'int',
					'length'  => null,
					'default' => 0
				],
				'dbtech_ecommerce_is_distributor' => [
					'type'    => 'tinyint',
					'length'  => null,
					'default' => 0
				],
				'dbtech_ecommerce_api_key'        => [
					'type'    => 'int',
					'length'  => null,
					'default' => 0
				],
			],
			'keys'    => [
				'dbtech_ecommerce_product_count' => 'dbtech_ecommerce_product_count',
				'dbtech_ecommerce_license_count' => 'dbtech_ecommerce_license_count'
			]
		];
		
		$definitions['xf_user_option'] = [
			'columns' => [
				'dbtech_ecommerce_email_on_sale'                 => [
					'type'    => 'tinyint',
					'length'  => null,
					'default' => 1,
					'after'   => 'email_on_conversation'
				],
				'dbtech_ecommerce_order_email_reminder'          => [
					'type'    => 'tinyint',
					'length'  => null,
					'default' => 1,
					'after'   => 'dbtech_ecommerce_email_on_sale'
				],
				'dbtech_ecommerce_license_expiry_email_reminder' => [
					'type'    => 'tinyint',
					'length'  => null,
					'default' => 1,
					'after'   => 'dbtech_ecommerce_order_email_reminder'
				],
			]
		];
		
		return $definitions;
	}
	
	/**
	 *
	 */
	public function installStep4(): void
	{
		$this->applyRegistrationDefaults([
			'dbtech_ecommerce_email_on_sale' => true,
			'dbtech_ecommerce_order_email_reminder' => true,
			'dbtech_ecommerce_license_expiry_email_reminder' => true
		]);
	}

	/**
	 *
	 */
	public function installStep5(): void
	{
		$this->insertThreadType(
			'dbtech_ecommerce_product',
			'DBTech\eCommerce:Product',
			'DBTech/eCommerce',
			true
		);
		$this->insertThreadType(
			'dbtech_ecommerce_download',
			'DBTech\eCommerce:Download',
			'DBTech/eCommerce',
			true
		);
	}
	
	/**
	 * @return string[]
	 */
	protected function getInstallQueries(): array
	{
		return [
			"
				REPLACE INTO `xf_dbtech_ecommerce_category`
					(`category_id`,
					`title`,
					`description`,
					`parent_category_id`, `depth`, `lft`, `rgt`, `display_order`,
					`product_count`, `breadcrumb_data`,
					`thread_node_id`, `thread_prefix_id`,
					`always_moderate_create`, `always_moderate_update`,
					`product_filters`, `field_cache`, `prefix_cache`, `review_field_cache`)
				VALUES
					(1,
					'Example category',
					'This is an example eCommerce category. You can manage the eCommerce categories via the <a href=\"" . \XF::options()->boardUrl . "/admin.php?dbtech-ecommerce/categories/\" target=\"_blank\">Admin control panel</a>. From there, you can setup more categories or change the eCommerce options.',
					0, 0, 1, 2, 1,
					0, 0x613a303a7b7d,
					0, 0,
					0, 0,
					'', '', '', '')
			",
			"
				REPLACE INTO xf_purchasable
					(purchasable_type_id, purchasable_class, addon_id)
				VALUES
					('dbtech_ecommerce_order', 'DBTech\\\\eCommerce:Order', 'DBTech/eCommerce')
			"
		];
	}

	/**
	 * Returns true if permissions were modified, otherwise false.
	 *
	 * @return bool
	 */
	protected function applyPermissionsInstall(): bool
	{
		// Regular perms
		$this->applyGlobalPermission('dbtechEcommerce', 'view', 'general', 'viewNode');
		$this->applyGlobalPermission('dbtechEcommerce', 'viewProductAttach', 'general', 'viewNode');
		$this->applyGlobalPermission('dbtechEcommerce', 'purchase', 'general', 'viewNode');
		$this->applyGlobalPermission('dbtechEcommerce', 'useCoupons', 'general', 'viewNode');
		$this->applyGlobalPermission('dbtechEcommerce', 'download', 'forum', 'viewAttachment');
		$this->applyGlobalPermission('dbtechEcommerce', 'react', 'forum', 'react');
		$this->applyGlobalPermission('dbtechEcommerce', 'rate', 'forum', 'react');
		$this->applyGlobalPermission('dbtechEcommerce', 'add', 'forum', 'hardDeleteAnyPost');
		$this->applyGlobalPermission('dbtechEcommerce', 'uploadProductAttach', 'forum', 'postThread');
		$this->applyGlobalPermission('dbtechEcommerce', 'updateOwn', 'forum', 'editOwnPost');
		$this->applyGlobalPermission('dbtechEcommerce', 'tagOwnProduct', 'forum', 'tagOwnThread');
		$this->applyGlobalPermission('dbtechEcommerce', 'tagAnyProduct', 'forum', 'tagAnyThread');
		$this->applyGlobalPermission('dbtechEcommerce', 'manageOthersTagsOwnProd', 'forum', 'manageOthersTagsOwnThread');
		$this->applyGlobalPermission('dbtechEcommerce', 'deleteOwn', 'forum', 'deleteOwnPost');
		
		// Moderator perms
		$this->applyGlobalPermission('dbtechEcommerce', 'inlineMod', 'forum', 'inlineMod');
		$this->applyGlobalPermission('dbtechEcommerce', 'viewScheduled', 'forum', 'viewDeleted');
		$this->applyGlobalPermission('dbtechEcommerce', 'viewDeleted', 'forum', 'viewDeleted');
		$this->applyGlobalPermission('dbtechEcommerce', 'deleteAny', 'forum', 'deleteAnyPost');
		$this->applyGlobalPermission('dbtechEcommerce', 'undelete', 'forum', 'undelete');
		$this->applyGlobalPermission('dbtechEcommerce', 'hardDeleteAny', 'forum', 'hardDeleteAnyPost');
		$this->applyGlobalPermission('dbtechEcommerce', 'deleteAnyReview', 'forum', 'deleteAnyPost');
		$this->applyGlobalPermission('dbtechEcommerce', 'editAny', 'forum', 'editAnyPost');
		$this->applyGlobalPermission('dbtechEcommerce', 'updateAny', 'forum', 'editAnyPost');
		$this->applyGlobalPermission('dbtechEcommerce', 'reassign', 'forum', 'editAnyPost');
		$this->applyGlobalPermission('dbtechEcommerce', 'manageAnyTag', 'forum', 'manageAnyTag');
		$this->applyGlobalPermission('dbtechEcommerce', 'viewModerated', 'forum', 'viewModerated');
		$this->applyGlobalPermission('dbtechEcommerce', 'approveUnapprove', 'forum', 'approveUnapprove');
		$this->applyGlobalPermission('dbtechEcommerce', 'warn', 'forum', 'warn');
		
		// Admin perms
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'viewAnyIncomeStats', 'general', 'viewIps');
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'viewLicenses', 'general', 'viewIps');
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'editAnyLicenses', 'general', 'banUser');
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'addLicenses', 'general', 'banUser');
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'deleteLicenses', 'general', 'banUser');
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'downloadAnyLicenses', 'general', 'banUser');
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'viewDownloadLog', 'general', 'viewIps');
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'assignCart', 'general', 'editBasicProfile');
		$this->applyGlobalPermission('dbtechEcommerceAdmin', 'addStoreCredit', 'general', 'editBasicProfile');
		
		return true;
	}
	
	/**
	 * @return \Closure[]
	 */
	protected function getDefaultWidgetSetup(): array
	{
		return [
			'dbtech_ecommerce_list_top_products' => function ($key, array $options = [])
			{
				$options = array_replace([], $options);
				
				$this->createWidget(
					$key,
					'dbt_ecom_top_products',
					[
						'positions' => [
							'dbtech_ecommerce_overview_sidenav' => 100,
							'dbtech_ecommerce_category_sidenav' => 100,
						],
						'options' => $options
					]
				);
			},
			'dbtech_ecommerce_overview_latest_reviews' => function ($key, array $options = [])
			{
				$options = array_replace([], $options);
				
				$this->createWidget(
					$key,
					'dbt_ecom_latest_reviews',
					[
						'positions' => ['dbtech_ecommerce_overview_sidenav' => 200],
						'options' => $options
					]
				);
			},
			'dbtech_ecommerce_overview_top_authors' => function ($key, array $options = [])
			{
				$options = array_replace([
					'member_stat_key' => 'dbtech_ecommerce_most_products'
				], $options);
				
				$this->createWidget(
					$key,
					'member_stat',
					[
						'positions' => ['dbtech_ecommerce_overview_sidenav' => 300],
						'options' => $options
					]
				);
			},
			'dbtech_ecommerce_whats_new_overview_new_products' => function ($key, array $options = [])
			{
				$options = array_replace([
					'limit' => 10,
					'style' => 'full'
				], $options);
				
				$this->createWidget(
					$key,
					'dbt_ecom_new_products',
					[
						'positions' => ['whats_new_overview' => 200],
						'options' => $options
					]
				);
			},
			'dbtech_ecommerce_forum_overview_new_products' => function ($key, array $options = [])
			{
				$options = array_replace([], $options);
				
				$this->createWidget(
					$key,
					'dbt_ecom_new_products',
					[
						'positions' => [
							'forum_list_sidebar' => 38,
							'forum_new_posts_sidebar' => 28
						],
						'options' => $options
					]
				);
			},
		];
	}
	
	/**
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function runPostInstallActions(): void
	{
		/** @var \XF\Service\RebuildNestedSet $service */
		$service = \XF::service('XF:RebuildNestedSet', 'DBTech\eCommerce:Category', [
			'parentField' => 'parent_category_id'
		]);
		$service->rebuildNestedSetInfo();
		
		/** @var \DBTech\eCommerce\Repository\ProductPrefix $productPrefixRepo */
		$productPrefixRepo = \XF::repository('DBTech\eCommerce:ProductPrefix');
		$productPrefixRepo->rebuildPrefixCache();
		
		/** @var \DBTech\eCommerce\Repository\ProductField $productFieldRepo */
		$productFieldRepo = \XF::repository('DBTech\eCommerce:ProductField');
		$productFieldRepo->rebuildFieldCache();
		
		/** @var \DBTech\eCommerce\Repository\LicenseField $licenseFieldRepo */
		$licenseFieldRepo = \XF::repository('DBTech\eCommerce:LicenseField');
		$licenseFieldRepo->rebuildFieldCache();
		
		/** @var \DBTech\eCommerce\Repository\Country $countryRepo */
		$countryRepo = \XF::repository('DBTech\eCommerce:Country');
		$countryRepo->updateCountryList();
		$countryRepo->updateVatRates();
		
		/** @var \DBTech\eCommerce\Repository\GeoIp $geoIpRepo */
		$geoIpRepo = \XF::repository('DBTech\eCommerce:GeoIp');
		$geoIpRepo->geoIpUpdate();
	}
	
	/**
	 * @return string[]
	 */
	protected function getAdminPermissions(): array
	{
		return [
			'dbtechEcomBusiness' => 'option',
			'dbtechEcomCategory' => 'option',
			'dbtechEcomCoupon' => 'option',
			'dbtechEcomCredit' => 'option',
			'dbtechEcomDiscount' => 'option',
			'dbtechEcomDownload' => 'option',
			'dbtechEcomLicense' => 'option',
			'dbtechEcomProduct' => 'option',
			'dbtechEcomLogs' => 'option',
			'dbtechEcomSale' => 'option'
		];
	}
	
	/**
	 * @return string[]
	 */
	protected function getPermissionGroups(): array
	{
		return [
			'dbtechEcommerce',
			'dbtechEcommerceAdmin',
		];
	}
	
	/**
	 * @return string[]
	 */
	protected function getContentTypes(): array
	{
		return [
			'dbtech_ecommerce_attach',
			'dbtech_ecommerce_autogen',
			'dbtech_ecommerce_category',
			'dbtech_ecommerce_coupon',
			'dbtech_ecommerce_credit',
			'dbtech_ecommerce_dist',
			'dbtech_ecommerce_download',
			'dbtech_ecommerce_external',
			'dbtech_ecommerce_license',
			'dbtech_ecommerce_order',
			'dbtech_ecommerce_product',
			'dbtech_ecommerce_rating',
			'dbtech_ecommerce_sale',
			'dbtech_ecommerce_version'
		];
	}
	
	/**
	 * @return string[]
	 */
	protected function getRegistryEntries(): array
	{
		return [
			'dbtEcLicenseFieldsInfo',
			'dbtEcOrderFieldsInfo',
			'dbtEcPrefixes',
			'dbtEcProductFieldsInfo',
		];
	}
}