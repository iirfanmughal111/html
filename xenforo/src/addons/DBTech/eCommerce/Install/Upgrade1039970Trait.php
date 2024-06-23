<?php

namespace DBTech\eCommerce\Install;

use XF\Db\Schema\Alter;

/**
 * @property \XF\AddOn\AddOn addOn
 * @property \XF\App app
 *
 * @method \XF\Db\AbstractAdapter db()
 * @method \XF\Db\SchemaManager schemaManager()
 * @method \XF\Db\Schema\Column addOrChangeColumn($table, $name, $type = null, $length = null)
 */
trait Upgrade1039970Trait
{
	/**
	 *
	 */
	public function upgrade1030031Step1()
	{
		$this->migrateTableToReactions('xf_dbtech_ecommerce_download');
	}
	
	/**
	 *
	 */
	public function upgrade1030031Step2()
	{
		$this->migrateTableToReactions('xf_dbtech_ecommerce_product');
	}
	
	/**
	 *
	 */
	public function upgrade1030031Step3()
	{
		$this->renameLikeAlertOptionsToReactions(['dbtech_ecommerce_download', 'dbtech_ecommerce_product']);
	}
	
	/**
	 *
	 */
	public function upgrade1030031Step4()
	{
		$this->renameLikeAlertsToReactions(['dbtech_ecommerce_download', 'dbtech_ecommerce_product']);
	}
	
	/**
	 *
	 */
	public function upgrade1030031Step5()
	{
		// miscellaneous reaction migrations
		
		$this->renameLikePermissionsToReactions([
			'dbtechEcommerce' => true, // global and content
		]);
		
		$this->renameLikeStatsToReactions(['dbt_ecom_download', 'dbt_ecom_product']);
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1030031Step6(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:Category', ['prefix_cache', 'field_cache'], $position, $stepParams);
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1030031Step7(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:DownloadLog', ['license_fields'], $position, $stepParams);
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1030031Step8(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:License', ['license_fields'], $position, $stepParams);
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1030031Step9(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:OrderItem', ['product_fields'], $position, $stepParams);
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1030031Step10(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:Product', [
			'requirements',
			'field_cache',
			'product_filters',
			'shipping_zones',
			'tags'
		], $position, $stepParams);
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1030031Step11(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:ShippingZone', ['countries', 'shipping_methods'], $position, $stepParams);
	}
	
	/**
	 *
	 */
	public function upgrade1030031Step12()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_download_version', function (Alter $table)
		{
			$table->addColumn('attach_count', 'int')->setDefault(0);
		});
	}
	
	/**
	 * @throws \XF\Db\Exception
	 */
	public function upgrade1030031Step13()
	{
		$this->db()->query("
			UPDATE xf_dbtech_ecommerce_download_version AS version
			SET attach_count = (
				SELECT COUNT(*)
				FROM xf_attachment
				WHERE content_type = 'dbtech_ecommerce_version'
					AND content_id = version.download_version_id
			)
		");
	}
	
	/**
	 *
	 */
	public function upgrade1030031Step14()
	{
		$db = $this->db();
		
		$db->beginTransaction();
		
		$db->update('xf_user', ['dbtech_ecommerce_api_key' => 0], null);
		
		$db->commit();
	}
	
	/**
	 *
	 */
	public function upgrade1030031Step15()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_user', function (Alter $table)
		{
			$table->changeColumn('dbtech_ecommerce_api_key')->resetDefinition()->type('int')->setDefault(0);
		});
	}
	
	/**
	 *
	 */
	public function upgrade1030033Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_license', function (Alter $table)
		{
			$table->addColumn('sent_expiring_reminder', 'tinyint')->setDefault(0)->after('expiry_date');
			$table->addColumn('sent_expired_reminder', 'tinyint')->setDefault(0)->after('sent_expiring_reminder');
		});
		
		$this->db()->update('xf_dbtech_ecommerce_license', [
			'sent_expiring_reminder' => 1
		], 'expiry_date < ? AND expiry_date <> 0', [\XF::$time]);
		
		$this->db()->update('xf_dbtech_ecommerce_license', [
			'sent_expired_reminder' => 1
		], 'expiry_date < ? AND expiry_date <> 0', [\XF::$time - 604800]);
	}
	
	/**
	 *
	 */
	public function upgrade1030033Step2()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_user_option', function (Alter $table)
		{
			$table->addColumn('dbtech_ecommerce_license_expiry_email_reminder', 'tinyint', 3)->setDefault(1)->after('dbtech_ecommerce_order_email_reminder');
		});
	}

	/**
	 *
	 */
	public function upgrade1030033Step3()
	{
		$defaultValue = [
			'email_on_sale' => true,
			'order_email_reminder' => true,
			'license_expiry_email_reminder' => true
		];
		
		$this->query("
			UPDATE xf_option
			SET default_value = ?
			WHERE option_id = 'dbtechEcommerceRegistrationDefaults'
		", json_encode($defaultValue));
		
		$registrationDefaults = json_decode($this->db()->fetchOne("
			SELECT option_value
			FROM xf_option
			WHERE option_id = 'dbtechEcommerceRegistrationDefaults'
		"), true);
		
		$update = false;
		foreach (array_keys($defaultValue) AS $key)
		{
			if (!isset($registrationDefaults[$key]))
			{
				$update = true;
				$registrationDefaults[$key] = $defaultValue[$key];
			}
		}
		
		if ($update)
		{
			$this->query("
				UPDATE xf_option
				SET option_value = ?
				WHERE option_id = 'dbtechEcommerceRegistrationDefaults'
			", json_encode($registrationDefaults));
		}
	}
	
	/**
	 *
	 */
	public function upgrade1030034Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_order_item', function (Alter $table)
		{
			$table->addColumn('parent_order_item_id', 'int')->after('product_cost_id');
		});
		
		$this->db()->update('xf_dbtech_ecommerce_license', [
			'sent_expiring_reminder' => 1
		], 'expiry_date < ? AND expiry_date <> 0', [\XF::$time]);
		
		$this->db()->update('xf_dbtech_ecommerce_license', [
			'sent_expired_reminder' => 1
		], 'expiry_date < ? AND expiry_date <> 0', [\XF::$time - 604800]);
	}
	
	/**
	 *
	 */
	public function upgrade1030034Step2()
	{
		$sm = $this->schemaManager();
		
		$this->db()->update('xf_dbtech_ecommerce_download', [
			'reactions' => json_encode([])
		], 'reactions IS NULL');
		
		$this->db()->update('xf_dbtech_ecommerce_product', [
			'reactions' => json_encode([])
		], 'reactions IS NULL');
		
		$sm->alterTable('xf_dbtech_ecommerce_download', function (Alter $table)
		{
			$table->changeColumn('reactions')->resetDefinition()->type('blob');
		});
		
		$sm->alterTable('xf_dbtech_ecommerce_product', function (Alter $table)
		{
			$table->changeColumn('reactions')->resetDefinition()->type('blob');
		});
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1030034Step3(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:Download', ['reaction_users'], $position, $stepParams);
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1030034Step4(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:Product', ['reaction_users'], $position, $stepParams);
	}
	
	/**
	 *
	 */
	public function upgrade1030034Step5()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product_cost', function (Alter $table)
		{
			$table->addColumn('highlighted', 'tinyint')->after('cost_amount');
		});
	}
	
	/**
	 *
	 */
	public function upgrade1030051Step1()
	{
		// Copied from the previous step to ensure anyone who installed B4 will get this column as well
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product_cost', function (Alter $table)
		{
			$table->addColumn('highlighted', 'tinyint')->after('cost_amount');
		});
	}
	
	/**
	 *
	 */
	public function upgrade1030052Step1()
	{
		// Copied from the previous step to ensure anyone who installed B4 will get this column as well
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product_field', function (Alter $table)
		{
			$table->addColumn('filterable', 'tinyint', 3)->setDefault(0)->after('moderator_editable');
		});
	}

	/**
	 *
	 */
	public function upgrade1030070Step1()
	{
		$this->executeUpgradeQuery("
			UPDATE xf_user_alert
			SET content_type = 'user',
			    action = CONCAT_WS('_', 'dbt_ecom_download', action)
			WHERE content_type = 'dbtech_ecommerce_download'
				AND action IN('delete', 'undelete', 'edit')
		");
		
		$this->executeUpgradeQuery("
			DELETE FROM xf_user_alert
			WHERE content_type = 'dbtech_ecommerce_license'
				AND action IN('delete', 'undelete', 'edit', 'reassign_from', 'reassign_to')
		");
		
		$this->executeUpgradeQuery("
			UPDATE xf_user_alert
			SET content_type = 'user',
			    action = CONCAT_WS('_', 'dbt_ecom_product', action)
			WHERE content_type = 'dbtech_ecommerce_product'
				AND action IN('addon_move', 'delete', 'undelete', 'edit', 'move', 'reassign_from', 'reassign_to')
		");
		
		$this->executeUpgradeQuery("
			UPDATE xf_user_alert
			SET content_type = 'user',
			    action = CONCAT_WS('_', 'dbt_ecom_rating', action)
			WHERE content_type = 'dbtech_ecommerce_rating'
				AND action IN('delete', 'edit')
		");
	}
	
	/**
	 *
	 */
	public function upgrade1030170Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product_cost', function (Alter $table)
		{
			$table->addColumn('description', 'varchar', 255)
				->setDefault('')
				->after('title')
			;
		});
	}
	
	/**
	 *
	 */
	public function upgrade1030170Step2()
	{
		$this->db()->update('xf_dbtech_ecommerce_license', [
			'sent_expiring_reminder' => 0
		], 'expiry_date > ?', [\XF::$time]);
		
		$this->db()->update('xf_dbtech_ecommerce_license', [
			'sent_expired_reminder' => 0
		], 'expiry_date > ?', [\XF::$time]);
	}

	/**
	 *
	 */
	public function upgrade1030470Step1()
	{
		$defaultValue = [
			'enabled' => true,
			'globalDefault' => 0.000,
			'includeTax' => true,
			'enableVat' => true,
			'apiKey' => '',
			'enhancedVatValidation' => false,
		];
		
		$this->query("
			UPDATE xf_option
			SET default_value = ?
			WHERE option_id = 'dbtechEcommerceSalesTax'
		", json_encode($defaultValue));
		
		$salesTaxSetting = json_decode($this->db()->fetchOne("
			SELECT option_value
			FROM xf_option
			WHERE option_id = 'dbtechEcommerceSalesTax'
		"), true);
		
		$update = false;
		foreach (array_keys($defaultValue) AS $key)
		{
			if (!isset($salesTaxSetting[$key]))
			{
				$update = true;
				$salesTaxSetting[$key] = $defaultValue[$key];
			}
		}
		
		if ($update)
		{
			$this->query("
				UPDATE xf_option
				SET option_value = ?
				WHERE option_id = 'dbtechEcommerceSalesTax'
			", json_encode($salesTaxSetting));
		}
	}
	
	/**
	 * @param $previousVersion
	 * @param array $stateChanges
	 */
	protected function postUpgrade1030031($previousVersion, array &$stateChanges)
	{
		// since reactions have changed
		$this->app->jobManager()->enqueueUnique(
			'permissionRebuild',
			'XF:PermissionRebuild',
			[],
			false
		);
	}
}