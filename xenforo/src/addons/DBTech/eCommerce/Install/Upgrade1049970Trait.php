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
trait Upgrade1049970Trait
{
	/**
	 *
	 */
	public function upgrade1040031Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_address', function (Alter $table)
		{
			$table->addColumn('address_state', 'enum')
				->values(['visible', 'verified', 'moderated', 'deleted'])
				->setDefault('visible')
				->after('user_id')
			;
			$table->addColumn('sales_tax_id', 'varchar', 100);
			$table->addColumn('order_count', 'int')->setDefault(0);
		});
	}

	/**
	 *
	 */
	public function upgrade1040031Step2()
	{
		/** @noinspection SqlWithoutWhere */
		$this->executeUpgradeQuery("
			UPDATE `xf_dbtech_ecommerce_address` AS `address`
			INNER JOIN `xf_dbtech_ecommerce_order` AS `order` ON(`order`.`address_id` = `address`.`address_id` AND `order`.`sales_tax_id` <> '')
			SET `address`.`sales_tax_id` = `order`.`sales_tax_id`
		");
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 * @throws \XF\Db\Exception
	 */
	public function upgrade1040031Step3(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		$perPage = 250;
		
		$db = $this->db();
		
		if (!isset($stepParams['max']))
		{
			$stepParams['max'] = $db->fetchOne("
				SELECT MAX(order_id)
				FROM xf_dbtech_ecommerce_order
				WHERE address_id = 0
					AND sales_tax_id <> ''
			");
		}
		
		$orderIds = $db->fetchAllColumn($db->limit(
			"
				SELECT DISTINCT order_id
				FROM xf_dbtech_ecommerce_order
				WHERE order_id > ?
					AND address_id = 0
					AND sales_tax_id <> ''
				ORDER BY order_id
			",
			$perPage
		), $position);
		if (!$orderIds)
		{
			return true;
		}
		
		$db->beginTransaction();
		
		$queryResults = $db->query('
			SELECT *
			FROM xf_dbtech_ecommerce_order
			WHERE order_id IN (' . $db->quote($orderIds) . ')
			ORDER BY order_id
		');
		while ($result = $queryResults->fetch())
		{
			$db->insert('xf_dbtech_ecommerce_address', [
				'user_id' => $result['user_id'],
				'title' => 'My Address',
				'business_title' => 'My Business Title',
				'business_co' => '',
				'address1' => '',
				'address2' => '',
				'address3' => '',
				'address4' => '',
				'country_code' => '',
				'sales_tax_id' => $result['sales_tax_id'],
			]);
			
			$addressId = $db->lastInsertId();
			
			$db->update('xf_dbtech_ecommerce_order', [
				'address_id' => $addressId
			], 'order_id = ?', $result['order_id']);
		}
		
		$db->commit();
		
		$next = end($orderIds);
		
		return [
			$next,
			"{$next} / {$stepParams['max']}",
			$stepParams
		];
	}

	/**
	 *
	 */
	public function upgrade1040031Step4()
	{
		$this->executeUpgradeQuery("
			UPDATE xf_dbtech_ecommerce_address
			SET address_state = 'moderated'
			WHERE sales_tax_id <> ''
		");
	}

	/**
	 *
	 */
	public function upgrade1040031Step5()
	{
		$this->executeUpgradeQuery("
			INSERT INTO xf_approval_queue
				(content_type, content_id, content_date)
			SELECT 'dbtech_ecommerce_address', address_id, UNIX_TIMESTAMP()
			FROM xf_dbtech_ecommerce_address
			WHERE address_state = 'moderated'
		");
	}
	
	/**
	 *
	 */
	public function upgrade1040031Step6()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_order', function (Alter $table)
		{
			$table->dropColumns([
				'sales_tax_id'
			]);
		});
	}
	
	/**
	 *
	 */
	public function upgrade1040032Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product', function (Alter $table)
		{
			$table->addColumn('is_featured', 'tinyint', 3)
				->setDefault(0)
				->after('is_paid')
			;
		});
	}
	
	/**
	 *
	 */
	public function upgrade1040033Step1()
	{
		$sm = $this->schemaManager();
		
		$tables = $this->getTables();
		
		$key = 'xf_dbtech_ecommerce_product_feature_temp';
		$sm->createTable($key, $tables[$key]);
	}
	
	/**
	 *
	 */
	public function upgrade1040033Step2()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product', function (Alter $table)
		{
			$table->addColumn('cost_cache', 'mediumblob')
				->after('product_filters')
			;
		});
		
		$sm->alterTable('xf_dbtech_ecommerce_sale', function (Alter $table)
		{
			$table->addColumn('feature_products', 'tinyint', 3)
				->setDefault(0)
				->after('allow_auto_discount')
			;
			$table->addColumn('is_recurring', 'tinyint', 3)
				->setDefault(0)
				->after('feature_products')
			;
			$table->addColumn('recurring_length_amount', 'tinyint', 3)
				->after('is_recurring')
			;
			$table->addColumn('recurring_length_unit', 'enum')
				->values(['day', 'month', 'year', ''])
				->after('recurring_length_amount')
			;
		});
	}
	
	/**
	 *
	 */
	public function upgrade1040033Step3()
	{
		$db = $this->db();
		
		$db->beginTransaction();
		
		$db->update('xf_dbtech_ecommerce_product', [
			'cost_cache' => '[]'
		], null);
		
		$newCache = [];
		
		$productCosts = $db->fetchAll('
			SELECT *
			FROM xf_dbtech_ecommerce_product_cost
			ORDER BY cost_amount ASC
		');
		foreach ($productCosts as $cost)
		{
			$key = $cost['product_id'];
			$newCache[$key][] = $cost;
		}
		
		foreach ($newCache as $productId => $costs)
		{
			$db->update('xf_dbtech_ecommerce_product', [
				'cost_cache' => json_encode($costs)
			], 'product_id = ?', $productId);
		}
		
		$db->commit();
	}
	
	/**
	 *
	 */
	public function upgrade1040034Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_sale', function (Alter $table)
		{
			$table->addColumn('other_dates', 'mediumblob')
				->after('end_date')
			;
		});
	}
	
	/**
	 *
	 */
	public function upgrade1040035Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product_sale', function (Alter $table)
		{
			$table->changeColumn('sale_percent')
				->resetDefinition()
				->type('decimal', '5,2')
				->setDefault('0.00')
			;
		});
		
		$sm->alterTable('xf_dbtech_ecommerce_sale', function (Alter $table)
		{
			$table->changeColumn('sale_percent')
				->resetDefinition()
				->type('decimal', '5,2')
				->setDefault('0.00')
			;
		});
	}
	
	/**
	 * Re-do migrating licence fields from serialized to json
	 *
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1040037Step1(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:License', ['license_fields'], $position, $stepParams);
	}
	
	/**
	 * Re-do migrating licence fields from serialized to json
	 *
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1040037Step2(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:DownloadLog', ['license_fields'], $position, $stepParams);
	}
	
	/**
	 * Re-do migrating licence fields from serialized to json
	 *
	 * @param array $stepParams
	 *
	 * @return array|bool
	 */
	public function upgrade1040037Step3(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		
		return $this->entityColumnsToJson('DBTech\eCommerce:Product', ['product_fields'], $position, $stepParams);
	}
	
	/**
	 *
	 */
	public function upgrade1040037Step4()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product_cost', function (Alter $table)
		{
			$table->addColumn('renewal_type', 'enum')
				->values(['global', 'fixed', 'percentage'])
				->setDefault('global')
			;
			$table->addColumn('renewal_amount', 'decimal', '10,2')
				->nullable(true)
			;
		});
		
		$sm->alterTable('xf_dbtech_ecommerce_order_item', function (Alter $table)
		{
			$table->addColumn('base_price', 'decimal', '10,2')->after('product_fields');
			$table->addColumn('sale_discount', 'decimal', '10,2')->after('base_price');
			$table->addColumn('coupon_discount', 'decimal', '10,2')->after('sale_discount');
			$table->addColumn('shipping_cost', 'decimal', '10,2')->after('coupon_discount');
			$table->addColumn('taxable_price', 'decimal', '10,2')->after('shipping_cost');
			$table->addColumn('sales_tax', 'decimal', '10,2')->after('taxable_price');
			$table->addColumn('price', 'decimal', '10,2')->after('sales_tax');
			$table->addColumn('currency', 'char', '3')->after('price');
		});
	}
	
	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 * @throws \XF\Db\Exception
	 */
	public function upgrade1040037Step5(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		$perPage = 250;
		
		$db = $this->db();
		
		if (!isset($stepParams['max']))
		{
			$stepParams['max'] = $db->fetchOne("
				SELECT MAX(order_item_id)
				FROM xf_dbtech_ecommerce_order_item
				WHERE extra_data != '[]'
			");
		}
		
		$orderItemIds = $db->fetchAllColumn($db->limit(
			"
				SELECT DISTINCT order_item_id
				FROM xf_dbtech_ecommerce_order_item
				WHERE order_item_id > ?
					AND extra_data != '[]'
				ORDER BY order_item_id
			",
			$perPage
		), $position);
		if (!$orderItemIds)
		{
			return true;
		}
		
		$db->beginTransaction();
		
		$queryResults = $db->query('
			SELECT `order_item`.*, `order`.`currency`
			FROM xf_dbtech_ecommerce_order_item AS `order_item`
			INNER JOIN xf_dbtech_ecommerce_order AS `order` USING(`order_id`)
			WHERE `order_item`.`order_item_id` IN (' . $db->quote($orderItemIds) . ')
			ORDER BY `order_item`.`order_item_id`
		');
		while ($result = $queryResults->fetch())
		{
			$data = \GuzzleHttp\json_decode($result['extra_data'], true);
			if (empty($data))
			{
				continue;
			}
			
			foreach ($data as &$var)
			{
				$var = abs(floatval(sprintf("%.2f", $var)));
			}
			
			$updateData = $data;
			$updateData['extra_data'] = \GuzzleHttp\json_encode($data);
			$updateData['currency'] = $result['currency'];
			
			unset($updateData['discounted_price']);
			
			$db->update('xf_dbtech_ecommerce_order_item', $updateData, 'order_item_id = ?', $result['order_item_id']);
		}
		
		$db->commit();
		
		$next = end($orderItemIds);
		
		return [
			$next,
			"{$next} / {$stepParams['max']}",
			$stepParams
		];
	}
	
	/**
	 * @throws \XF\Db\Exception
	 */
	public function upgrade1040037Step6()
	{
		$this->db()->query("
			UPDATE xf_user_alert
			SET depends_on_addon_id = 'DBTech/eCommerce'
			WHERE depends_on_addon_id = ''
				AND (content_type LIKE 'dbtech_ecommerce_%' OR `action` LIKE 'dbt_ecom_%')
		");
	}
	
	/**
	 *
	 */
	public function upgrade1040037Step7()
	{
		// Reset this cache
		\XF::app()->fs()->deleteDir('internal-data://dbtechEcommerce/releases');
	}
	
	/**
	 *
	 */
	public function upgrade1040037Step8()
	{
		$registrationDefaults = json_decode($this->db()->fetchOne("
			SELECT option_value
			FROM xf_option
			WHERE option_id = 'dbtechEcommerceRegistrationDefaults'
		"), true);
		
		$this->applyRegistrationDefaults([
			'dbtech_ecommerce_email_on_sale' => (
				isset($registrationDefaults['email_on_sale'])
				? (bool)$registrationDefaults['email_on_sale']
				: true
			),
			'dbtech_ecommerce_order_email_reminder' => (
				isset($registrationDefaults['order_email_reminder'])
				? (bool)$registrationDefaults['order_email_reminder']
				: true
			),
			'dbtech_ecommerce_license_expiry_email_reminder' => (
				isset($registrationDefaults['license_expiry_email_reminder'])
				? (bool)$registrationDefaults['license_expiry_email_reminder']
				: true
			),
		]);
	}
	
	/**
	 *
	 */
	public function upgrade1040052Step1()
	{
		$this->applyTables();
	}
	
	/**
	 *
	 */
	public function upgrade1040053Step1()
	{
		$this->applyTables();
	}

	/**
	 *
	 */
	public function upgrade1040053Step2()
	{
		$this->query("
			UPDATE xf_dbtech_ecommerce_download AS download
			LEFT JOIN xf_dbtech_ecommerce_product AS product USING(product_id)
			SET download.user_id = product.user_id
			WHERE product.user_id IS NOT NULL
		");
	}
	
	/**
	 *
	 */
	public function upgrade1040053Step3()
	{
		// Reset this cache
		\XF::app()->fs()->deleteDir('internal-data://dbtechEcommerce/releases');
	}
	
	/**
	 *
	 */
	public function upgrade1040170Step1()
	{
		$option = \XF::options()->dbtechEcommerceOrderCleanUp;
		
		$cutOff = strtotime('-' . $option['inactive_length_amount'] . ' ' . $option['inactive_length_unit'], \XF::$time);
		
		$this->db()->update('xf_dbtech_ecommerce_order', [
			'sent_reminder' => 1
		], 'order_state = \'pending\' AND order_date < ?', $cutOff);
	}
	
	/**
	 * @param bool $applied
	 * @param int|null $previousVersion
	 *
	 * @return bool
	 */
	protected function applyPermissionsUpgrade1049970(bool &$applied, ?int $previousVersion = null): bool
	{
		if (!$previousVersion || $previousVersion < 1040031)
		{
			// Normally you'd do !$previousVersion as well, but I want to base this off of existing permissions
			$this->applyGlobalPermission('dbtechEcommerce', 'addressWithoutApproval', 'dbtechEcommerce', 'approveUnapprove');
			
			$applied = true;
		}
		
		if (!$previousVersion || $previousVersion < 1040039)
		{
			// Normally you'd do !$previousVersion as well, but I want to base this off of existing permissions
			$this->applyGlobalPermission('dbtechEcommerce', 'viewScheduled', 'dbtechEcommerce', 'viewDeleted');
			
			$applied = true;
		}
		
		return $applied;
	}
	
	/**
	 * @param $previousVersion
	 * @param array $stateChanges
	 */
	protected function postUpgrade1040031($previousVersion, array &$stateChanges)
	{
		$this->app->jobManager()->enqueueUnique(
			'dbtechEcommerceRebuildAddresses',
			'DBTech\eCommerce:Address',
			[],
			false
		);
	}
}