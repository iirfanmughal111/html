<?php

namespace DBTech\eCommerce\Import\Importer;

use XF\Import\StepState;

/**
 * Class XrProductManager
 *
 * @package DBTech\eCommerce\Import\Importer
 */
class XrProductManager extends AbstractCoreImporter
{
	/** @var \XF\Db\Mysqli\Adapter */
	protected $sourceDb;
	
	/**
	 * @return array
	 */
	public static function getListInfo(): array
	{
		return [
			'target' => 'DragonByte eCommerce',
			'source' => 'XR Product Manager (2.0.0+)',
			'beta'   => true
		];
	}
	
	/**
	 * @return array
	 */
	protected function getBaseConfigDefault(): array
	{
		return [
			'db' => [
				'host' => '',
				'username' => '',
				'password' => '',
				'dbname' => '',
				'port' => 3306
			],
			'data_dir' => '',
			'internal_data_dir' => ''
		];
	}
	
	/**
	 * @param array $baseConfig
	 * @param array $errors
	 *
	 * @return bool
	 */
	public function validateBaseConfig(array &$baseConfig, array &$errors): bool
	{
		$fullConfig = array_replace_recursive($this->getBaseConfigDefault(), $baseConfig);
		$missingFields = false;
		$sourceDb = null;
		
		if ($fullConfig['db']['host'])
		{
			$validDbConnection = false;
			
			try
			{
				$sourceDb = new \XF\Db\Mysqli\Adapter($fullConfig['db'], false);
				$sourceDb->getConnection();
				$validDbConnection = true;
			}
			catch (\XF\Db\Exception $e)
			{
				$errors[] = \XF::phrase('source_database_connection_details_not_correct_x', ['message' => $e->getMessage()]);
			}
			
			if ($validDbConnection)
			{
				try
				{
					$sourceDb->fetchOne("SELECT option_value FROM xf_option WHERE option_id = 'currentVersionId'");
				} /** @noinspection PhpRedundantCatchClauseInspection */
				catch (\XF\Db\Exception $e)
				{
					if ($fullConfig['db']['dbname'] === '')
					{
						$errors[] = \XF::phrase('please_enter_database_name');
					}
					else
					{
						$errors[] = \XF::phrase('table_prefix_or_database_name_is_not_correct');
					}
				}
			}
		}
		else
		{
			$missingFields = true;
		}
		
		if ($fullConfig['data_dir'])
		{
			$data = rtrim($fullConfig['data_dir'], '/\\ ');
			
			if (!file_exists($data) || !is_dir($data))
			{
				$errors[] = \XF::phrase('directory_x_not_found_is_not_readable', ['dir' => $data]);
			}
			elseif (!file_exists("$data/avatars") || !file_exists("$data/attachments"))
			{
				$errors[] = \XF::phrase('directory_x_does_not_contain_expected_contents', ['dir' => $data]);
			}
			
			$baseConfig['data_dir'] = $data; // to make sure it takes the format we expect
		}
		else
		{
			$missingFields = true;
		}
		
		if ($fullConfig['internal_data_dir'])
		{
			$internalData = rtrim($fullConfig['internal_data_dir'], '/\\ ');
			
			if (!file_exists($internalData) || !is_dir($internalData))
			{
				$errors[] = \XF::phrase('directory_x_not_found_is_not_readable', ['dir' => $internalData]);
			}
			elseif (!file_exists("$internalData/install-lock.php"))
			{
				$errors[] = \XF::phrase('directory_x_does_not_contain_expected_contents', ['dir' => $internalData]);
			}
			
			$baseConfig['internal_data_dir'] = $internalData; // to make sure it takes the format we expect
		}
		else
		{
			$missingFields = true;
		}
		
		if ($missingFields)
		{
			$errors[] = \XF::phrase('please_complete_required_fields');
		}
		
		return $errors ? false : true;
	}
	
	/**
	 * @param array $vars
	 *
	 * @return string
	 */
	public function renderBaseConfigOptions(array $vars): string
	{
		if (empty($vars['baseConfig']))
		{
			$app = \XF::app();
			$dbConfig = $app->config('db');
			$vars['defaultConfig'] = [
				'db' => [
					'host' => $dbConfig['host'],
					'username' => $dbConfig['username'],
					'password' => $dbConfig['password'],
					'dbname' => $dbConfig['dbname'],
					'port' => 3306
				],
				'data_dir' => $app->config('externalDataPath') ?: 'data',
				'internal_data_dir' => $app->config('internalDataPath') ?: 'internal_data',
			];
		}

		return $this->app->templater()->renderTemplate('admin:dbtech_ecommerce_import_config_xenforo2', $vars);
	}
	
	/**
	 * @return array
	 */
	protected function getStepConfigDefault(): array
	{
		return [
			'products' => [
				'product_type' => 'dbtech_ecommerce_digital'
			]
		];
	}
	
	/**
	 * @param array $vars
	 *
	 * @return mixed
	 */
	public function renderStepConfigOptions(array $vars): string
	{
		return '';
	}
	
	/**
	 * @param array $steps
	 * @param array $stepConfig
	 * @param array $errors
	 *
	 * @return bool
	 */
	public function validateStepConfig(array $steps, array &$stepConfig, array &$errors): bool
	{
		return true;
	}
	
	/**
	 *
	 */
	protected function doInitializeSource()
	{
		$dbConfig = $this->baseConfig['db'];
		
		$this->sourceDb = new \XF\Db\Mysqli\Adapter($dbConfig, false);
	}
	
	/**
	 * @return array
	 */
	public function getSteps(): array
	{
		return [
			'permissions' => [
				'title' => \XF::phrase('permissions'),
			],
			'licenseFields' => [
				'title' => \XF::phrase('dbtech_ecommerce_license_fields'),
			],
			'productFields' => [
				'title' => \XF::phrase('dbtech_ecommerce_product_fields'),
			],
			'categories' => [
				'title' => \XF::phrase('dbtech_ecommerce_categories')
			],
			'products' => [
				'title' => \XF::phrase('dbtech_ecommerce_products')
			],
			'productFieldValues' => [
				'title' => \XF::phrase('dbtech_ecommerce_product_field_values'),
			],
			'productScreenshots' => [
				'title' => \XF::phrase('dbtech_ecommerce_product_screenshots'),
				'depends' => ['products']
			],
			'downloads' => [
				'title' => \XF::phrase('dbtech_ecommerce_downloads'),
				'depends' => ['products']
			],
			'coupons' => [
				'title' => \XF::phrase('dbtech_ecommerce_coupons'),
				'depends' => ['products']
			],
			'addOns' => [
				'title' => \XF::phrase('dbtech_ecommerce_add_on_products'),
				'depends' => ['products', 'downloads']
			],
			'orders' => [
				'title' => \XF::phrase('dbtech_ecommerce_orders'),
				'depends' => ['products', 'addOns']
			],
			'orderItems' => [
				'title' => \XF::phrase('dbtech_ecommerce_order_items'),
				'depends' => ['products', 'orders', 'addOns', 'licenses']
			],
			'transactions' => [
				'title' => \XF::phrase('dbtech_ecommerce_transactions'),
				'depends' => ['orders']
			],
			'licenses' => [
				'title' => \XF::phrase('dbtech_ecommerce_purchased_licenses'),
				'depends' => ['products', 'downloads', 'licenseFields', 'orders']
			],
			'addOnLicenses' => [
				'title' => \XF::phrase('dbtech_ecommerce_add_on_licenses'),
				'depends' => ['products', 'downloads', 'licenseFields', 'orders', 'licenses']
			],
			'downloadLogs' => [
				'title' => \XF::phrase('dbtech_ecommerce_download_log_entries'),
				'depends' => ['products', 'downloads', 'licenses', 'licenseFields']
			]
		];
	}
	
	
	// ########################### STEP: NODE PERMISSIONS ###############################
	
	/**
	 * @param StepState $state
	 *
	 * @return StepState
	 */
	public function stepPermissions(StepState $state): StepState
	{
		$entries = $this->sourceDb->fetchAll("
			SELECT *
			FROM xf_permission_entry
			WHERE permission_group_id = 'xr_pm'
		");
		
		$groupedEntries = [];
		foreach ($entries AS $entry)
		{
			$newPermissionId = $this->decodePermissionEntry($entry);
			
			if (!$newPermissionId)
			{
				continue;
			}
			
			if (!is_array($newPermissionId))
			{
				$newPermissionId = [$newPermissionId];
			}
			
			foreach ($newPermissionId as $permissionId)
			{
				$value = $entry['permission_value'];
				if ($value == 'use_int')
				{
					$value = $entry['permission_value_int'];
				}
				
				$groupedEntries[$entry['user_group_id']]['dbtechEcommerce'][$permissionId] = $value;
			}
		}
		
		/** @var \XF\Import\DataHelper\Permission $permissionHelper */
		$permissionHelper = $this->dataManager->helper('XF:Permission');
		
		foreach ($groupedEntries as $userGroupId => $permissions)
		{
			$permissionHelper->insertUserGroupPermissions($userGroupId, $permissions, false);
		}
		
		return $state->complete();
	}
	
	
	// ########################### STEP: DISCOUNTS ###############################
	
	/**
	 * @param StepState $state
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepLicenseFields(StepState $state): StepState
	{
		/** @var \DBTech\eCommerce\Import\Data\LicenseField $import */
		$import = $this->newHandler('DBTech\eCommerce:LicenseField');
		$import->setTitle('License Alias', "Give your license a short but memorable name, e.g. \"My website's license\" to help you identify the correct license in Your Licenses.");

		$import->bulkSet([
			'field_id' => 'websiteAlias',
			'display_group' => 'list',
			'display_order' => 5,
			'field_type' => 'textbox',
			'match_type' => 'none',
			'max_length' => 256,
			'required' => 1,
			'user_editable' => 'yes',
			'moderator_editable' => 1,
			'display_template' => '{$value}'
		]);
		$import->save(false);

		/** @var \DBTech\eCommerce\Import\Data\LicenseField $import */
		$import = $this->newHandler('DBTech\eCommerce:LicenseField');
		$import->setTitle('Website URL', "The website you intend to use the product on.<br />\nExample: https://www.example.com");
		
		$import->bulkSet([
			'field_id' => 'website',
			'display_group' => 'list',
			'display_order' => 10,
			'field_type' => 'textbox',
			'match_type' => 'url',
			'max_length' => 256,
			'required' => 1,
			'user_editable' => 'yes',
			'moderator_editable' => 1,
			'display_template' => '<a href="{$value}" target="_blank">{$value}</a>'
		]);
		$import->save(false);
		
		$state->imported++;
		
		return $state->complete();
	}
	
	
	// ########################### STEP: CATEGORIES ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndCategories(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(category_id)
			FROM xf_xr_pm_category
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepCategories(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$categories = $this->getCategories($state->startAfter, $state->end, $limit);
		
		if (!$categories)
		{
			return $state->complete();
		}
		
		foreach ($categories AS $oldCategoryId => $category)
		{
			$state->startAfter = $oldCategoryId;
			
			/** @var \DBTech\eCommerce\Import\Data\Category $import */
			$import = $this->newHandler('DBTech\eCommerce:Category');
			
			$import->bulkSet($this->mapXfKeys($category, [
				'category_id',
				'title',
				'description',
				'parent_category_id',
				'display_order',
				'lft',
				'rgt',
				'depth',
				'product_count',
				'last_update',
				'last_product_title',
				'last_product_id',
			]));
			
			$breadcrumbData = \XF\Util\Php::safeUnserialize($category['breadcrumb_data']);
			$import->breadcrumb_data = $breadcrumbData ?: [];
			
			$fieldCache = [];
			
			$productFields = $this->sourceDb->fetchAllKeyed("
				SELECT product_field.*
				FROM xf_xr_pm_product_field AS
					product_field
			", 'field_id');
			foreach ($productFields as $productField)
			{
				$fieldCache[$productField['field_id']] = $productField['field_id'];
			}
			
			$import->field_cache = $fieldCache;
			
			if ($newCategoryId = $import->save($oldCategoryId))
			{
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getCategories(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT category.*
			FROM xf_xr_pm_category AS
				category
			WHERE category.category_id > ? AND category.category_id <= ?
			ORDER BY category.category_id
			LIMIT {$limit}
		", 'category_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: PRODUCT FIELDS ###############################
	
	/**
	 * @return string|int
	 */
	public function getStepEndProductFields()
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(field_id)
			FROM xf_xr_pm_product_field
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepProductFields(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$productFields = $this->getProductFields($state->startAfter, $state->end, $limit);
		
		if (!$productFields)
		{
			return $state->complete();
		}
		
		foreach ($productFields AS $oldProductFieldId => $productField)
		{
			$state->startAfter = $oldProductFieldId;
			
			/** @var \DBTech\eCommerce\Import\Data\ProductField $import */
			$import = $this->newHandler('DBTech\eCommerce:ProductField');
			
			$import->bulkSet($this->mapXfKeys($productField, [
				'field_id',
				'display_order',
				'field_type',
				'match_type',
				'max_length',
				'required',
				'display_template',
			]));

			if (!in_array($productField['display_group'], [
				'above_main', 'above_info', 'below_info', 'below_main', 'new_tab', 'custom'
			]))
			{
				$import->display_group = 'below_info';
			}
			else
			{
				$import->display_group = $productField['display_group'];
			}

			$import->match_params = $this->decodeValue($productField['match_params'], 'json-array');
			$import->field_choices = $this->decodeValue($productField['field_choices'], 'serialized-json-array');

			$description = isset($productField['description']) ? $productField['description'] : null;
			if (isset($productField['title']))
			{
				$import->setTitle($productField['title'], $description);
			}

			if ($newProductFieldId = $import->save($oldProductFieldId))
			{
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getProductFields(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT product_field.*
			FROM xf_xr_pm_product_field AS
				product_field
			WHERE product_field.field_id > ? AND product_field.field_id <= ?
			ORDER BY product_field.field_id
			LIMIT {$limit}
		", 'field_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: PRODUCTS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndProducts(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(product_id)
			FROM xf_xr_pm_product
		") ?: 0;
	}

	/**
	 * @param int $key
	 * @param string|null $label
	 *
	 * @return string
	 */
	protected function mapXPMBranch(int $key, ?string $label): string
	{
		if ($label === null)
		{
			$label = $this->db()->fetchOne('SELECT label FROM xenproduct_branch WHERE branch_id = ?', $key);
		}

		switch ($label)
		{
			case 'XenForo 1.x':
				return 'xf1';
			case 'XenForo 2.x':
				return 'xf2';
			default:
				return 'b' . $key;
		}
	}

	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepProducts(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$products = $this->getProducts($state->startAfter, $state->end, $limit);
		
		if (!$products)
		{
			return $state->complete();
		}

		if (!$state->imported && \XF::db()->getSchemaManager()->tableExists('xenproduct_branch'))
		{
			// Xen Product Manager Essentials 'branch' support projected into the default category filters
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category = $this->app->find('DBTech\eCommerce:Category', 1);
			$filters = $category->product_filters;

			/** @noinspection SqlResolve */
			$extraProductFilters = $this->sourceDb->fetchPairs("
					SELECT branch_id, label 
					FROM xenproduct_branch");
			foreach ($extraProductFilters as $key => $label)
			{
				$key = $this->mapXPMBranch($key, $label);
				if (!isset($filters[$key]))
				{
					$filters[$key] = $label;
				}
			}
			$category->product_filters = $filters;

			$category->saveIfChanged();
		}

		/** @var \DBTech\eCommerce\Repository\ProductFilterMap $productFilterMapRepo */
		$productFilterMapRepo = \XF::repository('DBTech\eCommerce:ProductFilterMap');
		/** @var \DBTech\eCommerce\Import\DataHelper\Product $productHelper */
		$productHelper = $this->dataManager->helper('DBTech\eCommerce:Product');
		
		if (!isset($this->session->extra['xr_pm_imported_attachments']))
		{
			$this->session->extra['xr_pm_imported_attachments'] = [0];
		}
		
		$this->typeMap('category');
		
		foreach ($products AS $oldProductId => $product)
		{
			$state->startAfter = $oldProductId;
			
			if (!$mappedCategoryId = $this->lookupId('category', $product['category_id']))
			{
				continue;
			}
			
			$featureList = [];
			$features = \XF\Util\Php::safeUnserialize($product['features']);
			$features = $features ?: [];
			
			foreach ($features as $feature)
			{
				if (!empty($feature['item']))
				{
					$featureList[] = $feature['item'];
				}
			}
			
			/** @var \DBTech\eCommerce\Import\Data\Product $import */
			$import = $this->newHandler('DBTech\eCommerce:Product');
			
			$import->setDescription('');
			$import->setTagline($product['product_tag_line']);
			
			$import->bulkSet($this->mapXfKeys($product, [
				'title' => 'product_title',
				'description_full' => 'product_details',
				'copyright_info' => 'terms_conditions',
				'creation_date' => 'product_date',
				'last_update',
				'user_id',
				'username',
			]));
			
			$import->product_fields = $this->decodeValue($product['custom_fields'], 'serialized-json-array');
			$import->extra_group_ids = explode(',', $product['user_group_ids']);

			$productFilters = [];
			$productVersions = [];
			if (isset($product['current_branch_id']))
			{
				// Xen Product Manager Essentials 'branch' support

				/** @noinspection SqlResolve */
				$productVersions = $this->sourceDb->fetchPairs("
					select xenproduct_branch.branch_id,xenproduct_branch.label 
					from xenproduct_product_branch 
					join xenproduct_branch on xenproduct_branch.branch_id = xenproduct_product_branch.branch_id 
					where product_id = ?", $oldProductId);

				foreach ($productVersions as $key => $label)
				{
					$key = $this->mapXPMBranch($key, $label);
					$productFilters[] = $key;
					$import->addVersion($key, $label);
				}
			}
			if (!$productVersions)
			{
				$import->addVersion('_any', 'Any');
			}

			$import->bulkSet([
				'product_filters'		=> $productFilters,
				'product_versions'		=> $productVersions,
				'product_state' 		=> $this->decodeVisibleState($product['active']),
				'parent_product_id' 	=> 0,
				'product_specification' => $featureList ? ("[LIST]\n[*]" . implode("\n[*]", $featureList) . "\n[/LIST]") : '',
				'product_category_id' 	=> $mappedCategoryId,
				'license_prefix' 		=> 'L-XR-PM-' . $oldProductId,
				'is_discountable' 		=> 1,
				'is_paid' 				=> $product['price'] > 0.00,
				'has_demo' 				=> 0
			]);
			
			if (!$product['active'])
			{
				$import->setDeletionLogData([
					'delete_date'    => $product['product_date'],
					'delete_user_id' => $product['user_id'],
					'delete_reason'  => 'Defunct product'
				]);
			}
			
			if ($newProductId = $import->save($oldProductId))
			{
				/** @var \DBTech\eCommerce\Import\Data\ProductCost $costImport */
				$costImport = $this->newHandler('DBTech\eCommerce:ProductCost');
				
				$costImport->bulkSet($this->mapXfKeys($product, [
					'cost_amount' => 'price',
					'renewal_amount' => 'renew_price',
				]));
				
				$costImport->bulkSet([
					'product_id'   	=> $newProductId,
					'creation_date' => $product['product_date'],
					'renewal_type'  => 'fixed',
					'title' 		=> '',
					'stock' 		=> 0,
					'length_amount' => $product['duration'],
					'length_unit'   => $this->decodeCostUnit($product['duration_unit']),
				]);
				
				$costImport->save(false);
				
				if ($product['thumbnail_date'] && $attachment = $this->sourceDb->fetchRow("
					SELECT a.*,
						ad.*
					FROM xf_attachment AS a
					INNER JOIN xf_attachment_data AS ad ON (a.data_id = ad.data_id)
					WHERE a.content_id = ?
						AND a.content_type = 'xr_pm_product_thumb'
				", $oldProductId))
				{
					$sourceFile = $this->getSourceAttachmentDataPath(
						$attachment['data_id'],
						$attachment['file_path'],
						$attachment['file_hash']
					);
					if (!file_exists($sourceFile) || !is_readable($sourceFile))
					{
						continue;
					}
					
					/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
					$targetProduct = $this->em()->find('DBTech\eCommerce:Product', $newProductId);
					if ($targetProduct)
					{
						$productHelper->setIconFromFile($sourceFile, $targetProduct);
					}
					
					$this->em()->detachEntity($targetProduct);
					
					$this->session->extra['xr_pm_imported_attachments'][] = $attachment['attachment_id'];
				}
				
				$state->imported++;
			}

			// Xen Product Manager Essentials 'per-product' permissions support

			$entries = $this->sourceDb->fetchAll("
					SELECT *
					FROM xf_permission_entry_content
					WHERE content_type = 'xenproduct_product' and content_id = ? and permission_group_id = 'xenproduct'
				", [$oldProductId]);

			$contentPerms = [];
			foreach ($entries AS $entry)
			{
				$newPermissionId = $this->decodePermissionEntry($entry);

				if (!$newPermissionId)
				{
					continue;
				}

				if (!is_array($newPermissionId))
				{
					$newPermissionId = [$newPermissionId];
				}

				foreach ($newPermissionId as $permissionId)
				{
					$value = $entry['permission_value'];
					if ($value == 'use_int')
					{
						$value = $entry['permission_value_int'];
					}
					// ASSUME user groups have not changed
					$mappedUserGroupId = $entry['user_group_id'];
					$contentPerms[$newProductId][$mappedUserGroupId]['dbtechEcommerce'][$permissionId] = $value;
				}
			}

			/** @var \XF\Import\DataHelper\Permission $permissionHelper */
			$permissionHelper = $this->dataManager->helper('XF:Permission');

			foreach ($contentPerms as $contentId => $groupedPerms)
			{
				foreach ($groupedPerms as $userGroupId => $permissions)
				{
					$permissionHelper->insertContentUserGroupPermissions('dbtech_ecommerce_product', $contentId, $userGroupId, $permissions, false);
				}
			}

			// changing product filters loads/saves entities, so do this after the product is configured
			if ($productFilters)
			{
				$productFilterMapRepo->updateProductAssociations($newProductId, $productFilters);
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getProducts(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT product.*
			FROM xf_xr_pm_product AS
				product
			WHERE product.product_id > ? AND product.product_id <= ?
			ORDER BY product.product_id
			LIMIT {$limit}
		", 'product_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: PRODUCT FIELD VALUES ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndProductFieldValues(): int
	{
		return (int)$this->sourceDb->fetchOne("
			SELECT MAX(field_id)
			FROM xf_xr_pm_product_field
		") ?: 0;
	}

	/**
	 * @param \XF\Import\StepState $state
	 *
	 * @return \XF\Import\StepState
	 * @throws \Exception
	 */
	public function stepProductFieldValues(StepState $state): StepState
	{
		$productFieldValues = $this->getProductFieldValues();
		
		if (!$productFieldValues)
		{
			return $state->complete();
		}
		
		$this->typeMap('product');
		
		foreach ($productFieldValues AS $productFieldValue)
		{
			if (!$mappedProductId = $this->lookupId('product', $productFieldValue['product_id']))
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Import\Data\ProductFieldValue $import */
			$import = $this->newHandler('DBTech\eCommerce:ProductFieldValue');
			
			$import->bulkSet($this->mapXfKeys($productFieldValue, [
				'field_id',
				'field_value'
			]));
			
			$import->product_id = $mappedProductId;
			
			if ($newProductFieldValueId = $import->save(false))
			{
				$state->imported++;
			}
		}
		
		return $state->complete();
	}
	
	/**
	 * @return array
	 */
	protected function getProductFieldValues(): array
	{
		return $this->sourceDb->fetchAll("
			SELECT product_field_value.*
			FROM xf_xr_pm_product_field_value AS
				product_field_value
		");
	}
	
	
	// ########################### STEP: PRODUCT SCREENSHOTS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndProductScreenshots(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(attachment_id)
			FROM xf_attachment
			WHERE attachment_id NOT IN(" . $this->sourceDb->quote($this->session->extra['xr_pm_imported_attachments']) . ")
				AND content_type = 'xr_pm_product_image'
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepProductScreenshots(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$screenshots = $this->getProductScreenshots($state->startAfter, $state->end, $limit);
		
		if (!$screenshots)
		{
			return $state->complete();
		}
		
		$this->typeMap('product');
		
		foreach ($screenshots AS $oldScreenshotId => $screenshot)
		{
			$state->startAfter = $oldScreenshotId;
			
			if (!$mappedProductId = $this->lookupId('product', $screenshot['content_id']))
			{
				continue;
			}
			
			$sourceFile = $this->getSourceAttachmentDataPath(
				$screenshot['data_id'],
				$screenshot['file_path'],
				$screenshot['file_hash']
			);
			if (!file_exists($sourceFile) || !is_readable($sourceFile))
			{
				continue;
			}
			
			/** @var \XF\Import\Data\Attachment $import */
			$import = $this->newHandler('XF:Attachment');
			$import->bulkSet($this->mapKeys($screenshot, [
				'attach_date',
				'temp_hash',
				'unassociated',
				'view_count'
			]));
			$import->content_type = 'dbtech_ecommerce_product';
			$import->content_id = $mappedProductId;
			$import->setDataExtra('upload_date', $screenshot['upload_date']);
			$import->setDataUserId($screenshot['user_id']);
			$import->setSourceFile($sourceFile, $screenshot['filename']);
			
			$newId = $import->save(false);
			if ($newId)
			{
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getProductScreenshots(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT a.*,
				ad.*
			FROM xf_attachment AS a
			INNER JOIN xf_attachment_data AS ad ON (a.data_id = ad.data_id)
			WHERE a.attachment_id > ? AND a.attachment_id <= ?
				AND a.attachment_id NOT IN(" . $this->sourceDb->quote($this->session->extra['xr_pm_imported_attachments']) . ")
				AND a.content_type = 'xr_pm_product_image'
			ORDER BY a.attachment_id
			LIMIT {$limit}
		", 'attachment_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: DOWNLOADS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndDownloads(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(product_version_id)
			FROM xf_xr_pm_version
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepDownloads(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$downloads = $this->getDownloads($state->startAfter, $state->end, $limit);
		
		if (!$downloads)
		{
			return $state->complete();
		}
		
		$this->typeMap('product');
		
		/** @var \DBTech\eCommerce\Import\DataHelper\Download $downloadHelper */
		$downloadHelper = $this->dataManager->helper('DBTech\eCommerce:Download');
		
		foreach ($downloads AS $oldDownloadId => $download)
		{
			$state->startAfter = $oldDownloadId;
			
			if (!$mappedProductId = $this->lookupId('product', $download['product_id']))
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
			$targetProduct = $this->em()
				->find('DBTech\eCommerce:Product', $mappedProductId)
			;
			if (!$targetProduct)
			{
				continue;
			}
			
			$changeLog = [];
			if ($download['changelog'])
			{
				$changes = \XF\Util\Php::safeUnserialize($download['changelog']);
				if (is_array($changes))
				{
					foreach ($changes as $change)
					{
						if (!empty($change['item']) && !empty($change['visible']))
						{
							$changeLog[] = $change['item'];
						}
					}
				}
			}
			
			/** @var \DBTech\eCommerce\Import\Data\Download $import */
			$import = $this->newHandler('DBTech\eCommerce:Download');
			
			$import->bulkSet($this->mapXfKeys($download, [
				'version_string',
				'release_date',
				'release_notes' => 'version_details',
			]));
			
			$import->bulkSet([
				'download_state'       => 'visible',
				'product_id'           => $mappedProductId,
				'user_id'              => $targetProduct->user_id,
				'change_log'           => $changeLog ? ("[LIST]\n[*]" . implode("\n[*]", $changeLog) . "\n[/LIST]") : 'N/A',
				'has_new_features'     => 0,
				'has_changed_features' => 0,
				'has_bug_fixes'        => 0,
				'download_type'        => 'dbtech_ecommerce_attach',
				'discussion_thread_id' => 0
			]);
			
			if ($newDownloadId = $import->save($oldDownloadId))
			{
				$productVersion = '_any';
				if (isset($download['branch_id']))
				{
					// Xen Product Manager Essentials 'branch' support
					$productVersion = $this->mapXPMBranch($download['branch_id'], null);
				}

				$downloadHelper->importDownloadVersion($newDownloadId, [
					'product_id'           	=> $mappedProductId,
					'product_version'      	=> $productVersion,
					'product_version_type' 	=> 'full'
				]);
				
				$downloadVersionId = $this->db()->fetchOne("
					SELECT download_version_id
					FROM xf_dbtech_ecommerce_download_version
					WHERE download_id = ?
						AND product_id = ?
						AND product_version = ?
						AND product_version_type = 'full'
				", [$newDownloadId, $mappedProductId, $productVersion]);
				
				if ($attachments = $this->sourceDb->fetchAll("
					SELECT a.*,
						ad.*
					FROM xf_attachment AS a
					INNER JOIN xf_attachment_data AS ad ON (a.data_id = ad.data_id)
					WHERE a.content_id = ?
						AND a.content_type = 'xr_pm_product_version'
				", $oldDownloadId))
				{
					foreach ($attachments as $attachment)
					{
						$sourceFile = $this->getSourceAttachmentDataPath(
							$attachment['data_id'],
							$attachment['file_path'],
							$attachment['file_hash']
						);
						if (!file_exists($sourceFile) || !is_readable($sourceFile))
						{
							continue;
						}
						
						/** @var \XF\Import\Data\Attachment $attachImport */
						$attachImport = $this->newHandler('XF:Attachment');
						$attachImport->bulkSet($this->mapKeys($attachment, [
							'attach_date',
							'temp_hash',
							'unassociated',
							'view_count'
						]));
						$attachImport->content_type = 'dbtech_ecommerce_version';
						$attachImport->content_id = $downloadVersionId;
						$attachImport->setDataExtra('upload_date', $attachment['upload_date']);
						$attachImport->setDataUserId($attachment['user_id']);
						$attachImport->setSourceFile($sourceFile, $attachment['filename']);
						
						$attachImport->save(false);
					}
				}
				
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getDownloads(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT download.*
			FROM xf_xr_pm_version AS
				download
			WHERE download.product_version_id > ? AND download.product_version_id <= ?
			ORDER BY download.product_version_id
			LIMIT {$limit}
		", 'product_version_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: COUPONS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndCoupons(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(coupon_id)
			FROM xf_xr_pm_coupon
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepCoupons(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$coupons = $this->getCoupons($state->startAfter, $state->end, $limit);
		
		if (!$coupons)
		{
			return $state->complete();
		}
		
		$startDate = $this->db()->fetchOne('SELECT MIN(register_date) FROM xf_user');
		
		$groups = $this->db()->fetchAll("
			SELECT *
			FROM xf_user_group
			ORDER BY user_group_id
		");
		
		$this->typeMap('product');
		
		/** @var \XF\Import\DataHelper\Permission $permHelper */
		$permHelper = $this->dataManager->helper('XF:Permission');
		
		foreach ($coupons AS $oldCouponId => $coupon)
		{
			$state->startAfter = $oldCouponId;
			
			/** @var \DBTech\eCommerce\Import\Data\Coupon $import */
			$import = $this->newHandler('DBTech\eCommerce:Coupon');
			$import->setTitle($coupon['coupon_title']);
			
			$import->bulkSet($this->mapXfKeys($coupon, [
				'coupon_code',
			]));
			
			$import->bulkSet([
				'coupon_type' => $coupon['coupon_unit'] == 'percent' ? 'percent' : 'value',
				'coupon_percent' => $coupon['coupon_unit'] == 'percent' ? $coupon['coupon_reduction'] : 0,
				'coupon_value' => $coupon['coupon_unit'] != 'percent' ? $coupon['coupon_reduction'] : 0,
				'coupon_state' => $this->decodeVisibleState($coupon['active']),
				'discount_excluded' => 0,
				'allow_auto_discount' => 1,
				'start_date' => $startDate,
				'expiry_date' => $coupon['coupon_valid_to'] ?: 2147483647,
				'remaining_uses' => $coupon['coupon_set_limit'] ? $coupon['coupon_limit'] : -1,
				'minimum_products' => 0,
				'maximum_products' => 0,
				'minimum_cart_value' => 0,
				'maximum_cart_value' => 0
			]);
			if (!$coupon['active'])
			{
				$import->setDeletionLogData([
					'delete_date'    => $coupon['coupon_valid_to'] ?: \XF::$time,
					'delete_user_id' => 0,
					'delete_reason'  => 'Defunct coupon'
				]);
			}
			
			if ($coupon['coupon_type'] == 'product')
			{
				$allowedProducts = \XF\Util\Php::safeUnserialize($coupon['coupon_product_ids']) ?: [];
				
				foreach ($allowedProducts as $productId)
				{
					if (!$mappedProductId = $this->lookupId('product', $productId))
					{
						continue;
					}
					
					$import->addProduct($mappedProductId, 0);
				}
			}
			
			if ($newCouponId = $import->save($oldCouponId))
			{
				$allowedUserGroups = \XF\Util\Php::safeUnserialize($coupon['coupon_usable_by']) ?: [];
				
				if (!in_array(-1, $allowedUserGroups))
				{
					foreach ($groups as $userGroupId)
					{
						$permHelper->insertContentUserGroupPermissions(
							'dbtech_ecommerce_coupon',
							$newCouponId,
							$userGroupId,
							['dbtechEcommerce' => ['useCoupons' => in_array($userGroupId, $allowedUserGroups) ? 'content_allow' : 'reset']]
						);
					}
				}
				
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getCoupons(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT coupon.*
			FROM xf_xr_pm_coupon AS
				coupon
			WHERE coupon.coupon_id > ? AND coupon.coupon_id <= ?
			ORDER BY coupon.coupon_id
			LIMIT {$limit}
		", 'coupon_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: ADD-ONS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndAddOns(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(extra_id)
			FROM xf_xr_pm_optional_extra
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepAddOns(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 10): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$addOns = $this->getAddOns($state->startAfter, $state->end, $limit);
		
		if (!$addOns)
		{
			return $state->complete();
		}
		
		/** @var \DBTech\eCommerce\Import\DataHelper\Download $downloadHelper */
		$downloadHelper = $this->dataManager->helper('DBTech\eCommerce:Download');
		
		foreach ($addOns AS $oldAddOnId => $addOn)
		{
			$state->startAfter = $oldAddOnId;
			
			if (!isset($this->session->extra['xr_pm_optional_extras_map']))
			{
				$this->session->extra['xr_pm_optional_extras_map'] = [];
			}
			
			$parentProductIds = $this->sourceDb->fetchAllKeyed("
				SELECT *
				FROM xf_xr_pm_product_extra_map
				WHERE extra_id = ?
			", 'product_id', $oldAddOnId);
			
			foreach ($parentProductIds as $parentProductId => $null)
			{
				if (!$mappedProductId = $this->lookupId('product', $parentProductId))
				{
					continue;
				}
				
				/** @var \DBTech\eCommerce\Entity\Product $parentProduct */
				$parentProduct = $this->em()->find('DBTech\eCommerce:Product', $mappedProductId);
				
				if (!$parentProduct)
				{
					continue;
				}
				
				/** @var \DBTech\eCommerce\Import\Data\Product $import */
				$import = $this->newHandler('DBTech\eCommerce:Product');
				
				$import->setDescription('');
				$import->setTagline('');
				$import->addVersion('_any', 'Any');
				
				$import->bulkSet($this->mapXfKeys($addOn, [
					'title'                 => 'extra_title',
					'description_full'      => 'extra_description',
				]));
				
				$import->bulkSet([
					'creation_date'         => 'product_date',
					'product_state' 		=> $parentProduct->product_state,
					'user_id' 				=> $parentProduct->user_id,
					'username' 				=> $parentProduct->username,
					'copyright_info'      	=> '',
					'extra_group_ids'     	=> $addOn['extra_user_group_id'] ? [$addOn['extra_user_group_id']] : [],
					'parent_product_id'   	=> $parentProductId,
					'product_category_id' 	=> $parentProduct->product_category_id,
					'license_prefix'      	=> 'L-XR-PM-ADDON-' . $parentProductId . '-' . $oldAddOnId,
					'is_discountable'    	=> 1,
					'is_paid' 			  	=> $addOn['extra_price'] > 0.00,
					'has_demo'            	=> 0
				]);
				
				if ($parentProduct->product_state == 'deleted')
				{
					$import->setDeletionLogData([
						'delete_date'    => $parentProduct->creation_date,
						'delete_user_id' => $parentProduct->user_id,
						'delete_reason'  => 'Defunct add-on product'
					]);
				}
				
				if ($newAddOnId = $import->save(false))
				{
					if (!isset($this->session->extra['xr_pm_optional_extras_map'][$oldAddOnId]))
					{
						$this->session->extra['xr_pm_optional_extras_map'][$oldAddOnId] = [];
					}
					
					/** @var \DBTech\eCommerce\Import\Data\ProductCost $costImport */
					$costImport = $this->newHandler('DBTech\eCommerce:ProductCost');
					
					$costImport->bulkSet($this->mapXfKeys($addOn, [
						'cost_amount' => 'extra_price',
					]));
					
					$costImport->bulkSet([
						'product_id'    => $newAddOnId,
						'creation_date' => $parentProduct->creation_date,
						'title' 		=> '',
						'stock' 		=> 0,
						'length_amount' => $parentProduct->Costs->first()->length_amount,
						'length_unit'   => $parentProduct->Costs->first()->length_unit,
					]);
					
					$newCostId = $costImport->save(false);
					
					$this->session->extra['xr_pm_optional_extras_map'][$oldAddOnId][$parentProductId] = [
						'product_id' => $newAddOnId,
						'product_cost_id' => $newCostId,
					];
					
					if (in_array($addOn['extra_reward'], ['instructions', 'file']))
					{
						/** @var \DBTech\eCommerce\Import\Data\Download $downloadImport */
						$downloadImport = $this->newHandler('DBTech\eCommerce:Download');
						
						$downloadImport->bulkSet([
							'version_string'       => '1.0.0',
							'release_date'         => $parentProduct->creation_date,
							'discussion_thread_id' => 0,
							'download_state'       => 'visible',
							'product_id'           => $parentProduct->product_id,
							'user_id'              => $parentProduct->user_id,
							'change_log'           => 'Initial release.',
							'release_notes'        => '',
							'has_new_features'     => 0,
							'has_changed_features' => 0,
							'has_bug_fixes'        => 0,
							'download_type'        => 'dbtech_ecommerce_attach'
						]);
						
						$newDownloadId = $downloadImport->save(false);
						
						$downloadHelper->importDownloadVersion($newDownloadId, [
							'product_id'           	=> $parentProduct->product_id,
							'product_version'      	=> '_any',
							'product_version_type' 	=> 'full'
						]);
						
						$downloadVersionId = $this->db()->fetchOne("
							SELECT download_version_id
							FROM xf_dbtech_ecommerce_download_version
							WHERE download_id = ?
								AND product_id = ?
								AND product_version = '_any'
								AND product_version_type = 'full'
						", [$newDownloadId, $parentProduct->product_id]);
						
						if ($addOn['extra_reward'] == 'instructions')
						{
							$attachTempFile = \XF\Util\File::getTempFile();
							file_put_contents($attachTempFile, $addOn['instructions']);
							
							/** @var \XF\Import\Data\Attachment $attachImport */
							$attachImport = $this->newHandler('XF:Attachment');
							$attachImport->bulkSet([
								'content_type' => 'dbtech_ecommerce_version',
								'content_id'   => $downloadVersionId,
								'attach_date'  => $parentProduct->creation_date,
								'view_count'   => 0,
								'unassociated' => false
							]);
							
							$attachImport->setDataUserId($parentProduct->user_id);
							$attachImport->setSourceFile($attachTempFile, 'instructions.txt');
							
							$attachImport->save(false);
						}
						elseif ($addOn['extra_reward'] == 'file')
						{
							if ($attachments = $this->sourceDb->fetchAll("
								SELECT a.*,
									ad.*
								FROM xf_attachment AS a
								INNER JOIN xf_attachment_data AS ad ON (a.data_id = ad.data_id)
								WHERE a.content_id = ?
									AND a.content_type = 'xr_pm_product_extra'
							", $oldAddOnId))
							{
								foreach ($attachments as $attachment)
								{
									$sourceFile = $this->getSourceAttachmentDataPath(
										$attachment['data_id'],
										$attachment['file_path'],
										$attachment['file_hash']
									);
									if (!file_exists($sourceFile) || !is_readable($sourceFile))
									{
										continue;
									}
									
									/** @var \XF\Import\Data\Attachment $attachImport */
									$attachImport = $this->newHandler('XF:Attachment');
									$attachImport->bulkSet($this->mapKeys($attachment, [
										'attach_date',
										'temp_hash',
										'unassociated',
										'view_count'
									]));
									$attachImport->content_type = 'dbtech_ecommerce_version';
									$attachImport->content_id = $downloadVersionId;
									$attachImport->setDataExtra('upload_date', $attachment['upload_date']);
									$attachImport->setDataUserId($attachment['user_id']);
									$attachImport->setSourceFile($sourceFile, $attachment['filename']);
									
									$attachImport->save(false);
								}
							}
						}
					}
				}
				
				$this->em()->detachEntity($parentProduct);
			}
			
			$state->imported++;
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getAddOns(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT addon.*
			FROM xf_xr_pm_optional_extra AS
				addon
			WHERE addon.extra_id > ? AND addon.extra_id <= ?
			ORDER BY addon.extra_id
			LIMIT {$limit}
		", 'extra_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: ORDERS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndOrders(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(purchase_id)
			FROM xf_xr_pm_product_purchase
			WHERE purchase_type != 'extras'
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepOrders(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 10): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$orders = $this->getOrders($state->startAfter, $state->end, $limit);
		
		if (!$orders)
		{
			return $state->complete();
		}
		
		$this->typeMap('product');
		$this->typeMap('license');
		$this->typeMap('addon');
		
		/** @var \DBTech\eCommerce\Import\DataHelper\Order $orderHelper */
		$orderHelper = $this->dataManager->helper('DBTech\eCommerce:Order');
		
		foreach ($orders AS $oldOrderId => $order)
		{
			$state->startAfter = $oldOrderId;
			
			if (!$mappedProductId = $this->lookupId('product', $order['product_id']))
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
			$targetProduct = $this->em()->find('DBTech\eCommerce:Product', $mappedProductId);
			if (!$targetProduct)
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Import\Data\Order $import */
			$import = $this->newHandler('DBTech\eCommerce:Order');
			
			$import->bulkSet($this->mapXfKeys($order, [
				'user_id',
				'order_date' => 'purchase_date',
				'cost_amount' => 'total_price',
			]));
			
			$import->bulkSet([
				'ip_address' => '::1',
				'order_state' => $this->decodeOrderState($order['purchase_state']),
				'has_invoice' => 1,
				'sent_reminder' => 1,
				'store_credit_amount' => 0,
				'sub_total' => $order['product_price'] + $order['extras_price'],
				'sale_discounts' => 0,
				'coupon_discounts' => $order['discount_total'],
				'automatic_discounts' => 0,
				'sales_tax' => 0,
				'taxable_order_total' => $order['total_price'],
				'currency' => $order['purchase_currency'],
				'extra_data' => [
					'sub_total' => $order['total_price'],
					'sale_total' => 0,
					'coupon_discounts' => $order['discount_total'],
					'automatic_discounts' => 0,
					'sales_tax' => 0,
					'taxable_order_total' => $order['total_price'],
					'cost_currency' => $order['purchase_currency'],
				]
			]);
			
			if ($newOrderId = $import->save($oldOrderId))
			{
				$mappedLicenseId = $this->lookupId('license', $order['parent_purchase_id'], 0);
				
				$orderHelper->importOrderItem($newOrderId, [
					'upgradetype'       => $order['purchase_type'] == 'renewal' ? 'renew' : 'new',
					'user_id'           => $order['user_id'],
					'product_id'        => $mappedProductId,
					'product_cost_id'   => $targetProduct->Costs->first()->product_cost_id,
					'shipping_method_id'=> 0,
					'license_id'        => $mappedLicenseId,
					'coupon_id'         => 0,
					'product_fields'    => json_encode([]),
					'extra_data'        => [
						'base_price' => $order['product_price'],
						'sale_discount' => 0,
						'coupon_discount' => 0,
						'sales_tax' => 0,
						'discounted_price' => $order['product_price'],
						'taxable_price' => $order['product_price'],
						'price' => $order['product_price'],
					]
				]);
				
				$this->em()->detachEntity($targetProduct);
				
				if ($order['extras_price'] > 0.00)
				{
					$extras = json_decode($order['extras'], true);
					foreach ($extras as $extra)
					{
						if (empty($this->session->extra['xr_pm_optional_extras_map'][$extra['extra_id']][$mappedProductId]))
						{
							continue;
						}
						
						$extraInfo = $this->session->extra['xr_pm_optional_extras_map'][$extra['extra_id']][$mappedProductId];
						
						$orderHelper->importOrderItem($newOrderId, [
							'upgradetype'       => $order['purchase_type'] == 'renewal' ? 'renew' : 'new',
							'user_id'           => $order['user_id'],
							'product_id'        => $extraInfo['product_id'],
							'product_cost_id'   => $extraInfo['product_cost_id'],
							'shipping_method_id'=> 0,
							'license_id'        => $mappedLicenseId,
							'coupon_id'         => 0,
							'product_fields'    => json_encode([]),
							'extra_data'        => [
								'base_price' => $extra['extra_price'],
								'sale_discount' => 0,
								'coupon_discount' => 0,
								'sales_tax' => 0,
								'discounted_price' => $extra['extra_price'],
								'taxable_price' => $extra['extra_price'],
								'price' => $extra['extra_price'],
							]
						]);
					}
				}
				
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getOrders(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT purchase.*, parent_purchase.purchase_id AS parent_purchase_id
			FROM xf_xr_pm_product_purchase AS
				purchase
			LEFT JOIN xf_xr_pm_product_purchase AS parent_purchase
				ON(parent_purchase.purchase_key = purchase.parent_purchase_key)
			WHERE purchase.purchase_id > ? AND purchase.purchase_id <= ?
				AND purchase.purchase_type != 'extras'
			ORDER BY purchase.purchase_id
			LIMIT {$limit}
		", 'purchase_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: ORDER ITEMS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndOrderItems(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(purchase_id)
			FROM xf_xr_pm_product_purchase
			WHERE purchase_type = 'extras'
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepOrderItems(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 10): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$orderItems = $this->getOrderItems($state->startAfter, $state->end, $limit);
		
		if (!$orderItems)
		{
			return $state->complete();
		}
		
		$this->typeMap('product');
		$this->typeMap('license');
		$this->typeMap('addon');
		
		/** @var \DBTech\eCommerce\Import\DataHelper\Order $orderHelper */
		$orderHelper = $this->dataManager->helper('DBTech\eCommerce:Order');
		
		foreach ($orderItems AS $oldOrderId => $order)
		{
			$state->startAfter = $oldOrderId;
			
			if (!$mappedProductId = $this->lookupId('product', $order['product_id']))
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Import\Data\Order $import */
			$import = $this->newHandler('DBTech\eCommerce:Order');
			
			$import->bulkSet($this->mapXfKeys($order, [
				'user_id',
				'order_date' => 'purchase_date',
				'cost_amount' => 'total_price',
			]));
			
			$import->bulkSet([
				'ip_address' => '::1',
				'order_state' => $this->decodeOrderState($order['purchase_state']),
				'has_invoice' => 1,
				'sent_reminder' => 1,
				'store_credit_amount' => 0,
				'sub_total' => $order['product_price'] + $order['extras_price'],
				'sale_discounts' => 0,
				'coupon_discounts' => $order['discount_total'],
				'automatic_discounts' => 0,
				'sales_tax' => 0,
				'taxable_order_total' => $order['total_price'],
				'currency' => $order['purchase_currency'],
				'extra_data' => [
					'sub_total' => $order['total_price'],
					'sale_total' => 0,
					'coupon_discounts' => $order['discount_total'],
					'automatic_discounts' => 0,
					'sales_tax' => 0,
					'taxable_order_total' => $order['total_price'],
					'cost_currency' => $order['purchase_currency'],
				]
			]);
			
			if ($newOrderId = $import->save($oldOrderId))
			{
				$mappedLicenseId = $this->lookupId('license', $order['parent_purchase_id'], 0);
				
				$extras = json_decode($order['extras'], true);
				foreach ($extras as $extra)
				{
					if (empty($this->session->extra['xr_pm_optional_extras_map'][$extra['extra_id']][$mappedProductId]))
					{
						continue;
					}
					
					$extraInfo = $this->session->extra['xr_pm_optional_extras_map'][$extra['extra_id']][$mappedProductId];
					
					$orderHelper->importOrderItem($newOrderId, [
						'upgradetype'       => $order['purchase_type'] == 'renewal' ? 'renew' : 'new',
						'user_id'           => $order['user_id'],
						'product_id'        => $extraInfo['product_id'],
						'product_cost_id'   => $extraInfo['product_cost_id'],
						'shipping_method_id'=> 0,
						'license_id'        => $mappedLicenseId,
						'coupon_id'         => 0,
						'product_fields'    => json_encode([]),
						'extra_data'        => [
							'base_price' => $extra['extra_price'],
							'sale_discount' => 0,
							'coupon_discount' => 0,
							'sales_tax' => 0,
							'discounted_price' => $extra['extra_price'],
							'taxable_price' => $extra['extra_price'],
							'price' => $extra['extra_price'],
						]
					]);
				}
				
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getOrderItems(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT purchase.*, parent_purchase.purchase_id AS parent_purchase_id
			FROM xf_xr_pm_product_purchase AS
				purchase
			LEFT JOIN xf_xr_pm_product_purchase AS parent_purchase
				ON(parent_purchase.purchase_key = purchase.parent_purchase_key)
			WHERE purchase.purchase_id > ? AND purchase.purchase_id <= ?
				AND purchase.purchase_type = 'extras'
			ORDER BY purchase.purchase_id
			LIMIT {$limit}
		", 'purchase_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: TRANSACTIONS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndTransactions(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(purchase_request_id)
			FROM xf_purchase_request
			WHERE purchasable_type_id = 'xr_pm_product'
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepTransactions(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$transactions = $this->getTransactions($state->startAfter, $state->end, $limit);
		
		if (!$transactions)
		{
			return $state->complete();
		}
		
		$this->typeMap('order');
		
		/** @var \DBTech\eCommerce\Import\DataHelper\Order $orderHelper */
		$orderHelper = $this->dataManager->helper('DBTech\eCommerce:Order');
		
		foreach ($transactions AS $oldTransactionId => $transaction)
		{
			$state->startAfter = $oldTransactionId;
			
			if ($transaction['provider_id'] === null)
			{
				continue;
			}
			
			$extraData = json_decode($transaction['extra_data'], true);
			
			if (!$mappedOrderId = $this->lookupId('order', $extraData['purchase_id']))
			{
				continue;
			}
			
			/** @var \XF\Entity\PurchaseRequest $purchaseRequest */
			$purchaseRequest = $orderHelper->insertPurchaseRequest([
				'provider_id' => $transaction['provider_id'],
				'user_id' => $transaction['user_id'],
				'cost' => $transaction['cost_amount'],
				'currency' => $transaction['cost_currency'],
				'order_id' => $mappedOrderId
			]);
			
			$requestKey = $purchaseRequest->request_key;
			
			if (strlen($requestKey) > 32)
			{
				$requestKey = substr($requestKey, 0, 29) . '...';
			}
			
			/** @var \DBTech\eCommerce\Import\Data\PaymentProviderLog $import */
			$import = $this->newHandler('DBTech\eCommerce:PaymentProviderLog');
			
			$import->bulkSet($this->mapXfKeys($transaction, [
				'provider_id',
				'transaction_id',
				'subscriber_id',
				'log_type',
				'log_message',
				'log_details',
				'log_date'
			]));
			
			$import->purchase_request_key = $requestKey;
			
			if ($newTransactionId = $import->save(false))
			{
				if (in_array($transaction['log_type'], ['payment']))
				{
					/** @var \DBTech\eCommerce\Entity\Order $targetOrder */
					$targetOrder = $this->em()->find('DBTech\eCommerce:Order', $mappedOrderId);
					if ($targetOrder)
					{
						/** @var \DBTech\eCommerce\Entity\OrderItem $item */
						foreach ($targetOrder->Items as $item)
						{
							$logDetails = $item->extra_data;
							
							/** @var \DBTech\eCommerce\Import\Data\PurchaseLog $purchaseLogImport */
							$purchaseLogImport = $this->newHandler('DBTech\eCommerce:PurchaseLog');
							
							$purchaseLogImport->bulkSet([
								'order_id'  		=> $item->order_id,
								'order_item_id'  	=> $item->order_item_id,
								'product_id'  		=> $item->product_id,
								'user_id'     		=> $item->user_id,
								'log_date'    		=> $targetOrder->order_date,
								'cost_amount' 		=> $item->getPrice(),
								'log_type'    		=> $this->decodeOrderItemState($transaction['log_type'], $item->license_id),
								'log_details' 		=> $logDetails
							]);
							$purchaseLogImport->save(false);
						}
						
						$this->em()->detachEntity($targetOrder);
					}
				}
				
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getTransactions(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT purchase_request.*, payment_provider_log.*
			FROM xf_purchase_request
				AS purchase_request
			LEFT JOIN xf_payment_provider_log
				AS payment_provider_log
					ON(payment_provider_log.purchase_request_key = purchase_request.request_key)
			WHERE purchase_request.purchase_request_id > ? AND purchase_request.purchase_request_id <= ?
				AND purchasable_type_id = 'xr_pm_product'
			ORDER BY purchase_request.purchase_request_id
			LIMIT {$limit}
		", 'purchase_request_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: LICENSES ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndLicenses(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(purchase_id)
			FROM xf_xr_pm_product_purchase
			WHERE purchase_type = 'product'
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepLicenses(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$licenses = $this->getLicenses($state->startAfter, $state->end, $limit);
		
		if (!$licenses)
		{
			return $state->complete();
		}
		
		$this->typeMap('order');
		$this->typeMap('product');
		
		/** @var \DBTech\eCommerce\Import\DataHelper\License $licenseHelper */
		$licenseHelper = $this->dataManager->helper('DBTech\eCommerce:License');
		
		/** @var \DBTech\eCommerce\Repository\LicenseField $licenseFieldRepo */
		$licenseFieldRepo = $this->app->repository('DBTech\eCommerce:LicenseField');
		
		foreach ($licenses AS $oldLicenseId => $license)
		{
			$state->startAfter = $oldLicenseId;
			
			if (!$mappedProductId = $this->lookupId('product', $license['product_id']))
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
			$targetProduct = $this->em()->find('DBTech\eCommerce:Product', $mappedProductId);
			if (!$targetProduct)
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Import\Data\License $import */
			$import = $this->newHandler('DBTech\eCommerce:License');
			
			$import->bulkSet($this->mapXfKeys($license, [
				'user_id',
				'username',
				'expiry_date',
				'purchase_date',
			]));
			
			$import->bulkSet([
				'product_id' => $mappedProductId,
				'order_id' => $this->lookupId('order', $license['purchase_id'], 0),
				'license_state' => $this->decodeLicenseState($license['purchase_state']),
				'latest_download_id' => 0,
				'license_key' => $targetProduct->license_prefix  .
					$license['user_id'] .
					\strtoupper(\preg_replace('/[^A-Za-z0-9]/', 'A', \XF::generateRandomString(10)))
			]);
			
			if ($newLicenseId = $import->save($oldLicenseId))
			{
				$licenseHelper->importLicenseFieldValueBulk($newLicenseId, [
					[
						'field_id' => 'website',
						'field_value' => !empty($license['license_url']) ? $license['license_url'] : '',
					],
					[
						'field_id' => 'websiteAlias',
						'field_value' => !empty($license['license_name']) ? $license['license_name'] : '',
					]
				]);

				$licenseFieldRepo->rebuildLicenseFieldValuesCache($newLicenseId);
				
				$state->imported++;
			}
			
			$this->em()->detachEntity($targetProduct);

			$this->db()->insert('xf_dbtech_ecommerce_product_watch', [
				'user_id' => $license['user_id'],
				'product_id' => $mappedProductId,
				'email_subscribe' => $license['expiry_date'] > \XF::$time
			], false, false, 'IGNORE');

			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getLicenses(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT license.*
			FROM xf_xr_pm_product_purchase AS
				license
			WHERE license.purchase_id > ? AND license.purchase_id <= ?
				AND purchase_type = 'product'
			ORDER BY license.purchase_id
			LIMIT {$limit}
		", 'purchase_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: LICENSES ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndAddOnLicenses(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(purchase_id)
			FROM xf_xr_pm_product_purchase
			WHERE purchase_type = 'extra'
				OR extras != '[]'
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepAddOnLicenses(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 25): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$licenses = $this->getAddOnLicenses($state->startAfter, $state->end, $limit);
		
		if (!$licenses)
		{
			return $state->complete();
		}
		
		$this->typeMap('license');
		$this->typeMap('order');
		$this->typeMap('product');
		
		/** @var \DBTech\eCommerce\Import\DataHelper\License $licenseHelper */
		$licenseHelper = $this->dataManager->helper('DBTech\eCommerce:License');
		
		/** @var \DBTech\eCommerce\Repository\LicenseField $licenseFieldRepo */
		$licenseFieldRepo = $this->app->repository('DBTech\eCommerce:LicenseField');
		
		foreach ($licenses AS $oldLicenseId => $license)
		{
			$state->startAfter = $oldLicenseId;
			
			if (!$mappedProductId = $this->lookupId('product', $license['product_id']))
			{
				continue;
			}
			
			$extras = json_decode($license['extras'], true);
			foreach ($extras as $extra)
			{
				if (empty($this->session->extra['xr_pm_optional_extras_map'][$extra['extra_id']][$mappedProductId]))
				{
					continue;
				}
				
				$extraInfo = $this->session->extra['xr_pm_optional_extras_map'][$extra['extra_id']][$mappedProductId];
				
				/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
				$targetProduct = $this->em()->find('DBTech\eCommerce:Product', $extraInfo['product_id']);
				if (!$targetProduct)
				{
					continue;
				}
				
				/** @var \DBTech\eCommerce\Import\Data\License $import */
				$import = $this->newHandler('DBTech\eCommerce:License');
				
				$import->bulkSet($this->mapXfKeys($license, [
					'user_id',
					'username',
					'expiry_date',
					'purchase_date',
				]));
				
				$import->bulkSet([
					'product_id' => $targetProduct->product_id,
					'order_id' => $this->lookupId('order', $license['purchase_id'], 0),
					'license_state' => $this->decodeLicenseState($license['purchase_state']),
					'latest_download_id' => 0,
					'parent_license_id' => $this->lookupId('license', $license['purchase_id'], 0),
					'license_key' => $targetProduct->license_prefix  .
						$license['user_id'] .
						\strtoupper(\preg_replace('/[^A-Za-z0-9]/', 'A', \XF::generateRandomString(10)))
				]);
				
				if ($newLicenseId = $import->save(false))
				{
					$licenseHelper->importLicenseFieldValueBulk($newLicenseId, [
						[
							'field_id' => 'website',
							'field_value' => !empty($license['license_url']) ? $license['license_url'] : '',
						],
						[
							'field_id' => 'websiteAlias',
							'field_value' => !empty($license['license_name']) ? $license['license_name'] : '',
						]
					]);
					
					$licenseFieldRepo->rebuildLicenseFieldValuesCache($newLicenseId);
				}
				
				$this->em()->detachEntity($targetProduct);
			}
			
			$state->imported++;
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getAddOnLicenses(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT license.*
			FROM xf_xr_pm_product_purchase AS
				license
			WHERE license.purchase_id > ? AND license.purchase_id <= ?
				AND (license.purchase_type = 'extra' OR license.extras != '[]')
			ORDER BY license.purchase_id
			LIMIT {$limit}
		", 'purchase_id', [$startAfter, $end]);
	}
	
	
	// ########################### STEP: DOWNLOAD LOGS ###############################
	
	/**
	 * @return int
	 */
	public function getStepEndDownloadLogs(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(download_id)
			FROM xf_xr_pm_download
		") ?: 0;
	}
	
	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param int|null $maxTime
	 * @param int $limit
	 *
	 * @return StepState
	 * @throws \Exception
	 */
	public function stepDownloadLogs(StepState $state, array $stepConfig, ?int $maxTime, int $limit = 250): StepState
	{
		$timer = new \XF\Timer($maxTime);
		
		$downloadLogs = $this->getDownloadLogs($state->startAfter, $state->end, $limit);
		
		if (!$downloadLogs)
		{
			return $state->complete();
		}
		
		$this->typeMap('product');
		$this->typeMap('download');
		$this->typeMap('license');
		
		/** @var \DBTech\eCommerce\Import\DataHelper\DownloadLog $downloadLogHelper */
		$downloadLogHelper = $this->dataManager->helper('DBTech\eCommerce:DownloadLog');
		
		/** @var \DBTech\eCommerce\Repository\DownloadLog $downloadLogRepo */
		$downloadLogRepo = $this->app->repository('DBTech\eCommerce:DownloadLog');
		
		foreach ($downloadLogs AS $oldDownloadLogId => $downloadLog)
		{
			$state->startAfter = $oldDownloadLogId;
			
			if (!$mappedProductId = $this->lookupId('product', $downloadLog['product_id']))
			{
				continue;
			}
			
			if (!$mappedDownloadId = $this->lookupId('download', $downloadLog['version_id']))
			{
				continue;
			}
			
			if (!$mappedLicenseId = $this->lookupId('license', $downloadLog['purchase_id']))
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Import\Data\DownloadLog $import */
			$import = $this->newHandler('DBTech\eCommerce:DownloadLog');
			
			$import->bulkSet($this->mapXfKeys($downloadLog, [
				'user_id',
				'log_date' => 'last_download_date'
			]));

			$productVersion = '_any';
			if (isset($download['branch_id']))
			{
				// Xen Product Manager Essentials 'branch' support
				$productVersion = $this->mapXPMBranch($downloadLog['branch_id'], null);
			}

			$import->bulkSet([
				'product_id' => $mappedProductId,
				'product_version' => $productVersion,
				'download_id' => $mappedDownloadId,
				'license_id' => $mappedLicenseId
			]);
			
			if ($newDownloadLogId = $import->save($oldDownloadLogId))
			{
				$downloadLogHelper->importLicenseFieldValue($newDownloadLogId, [
					'field_id' => 'website',
					'field_value' => !empty($downloadLog['license_url']) ? $downloadLog['license_url'] : '',
				]);
				
				$downloadLogRepo->rebuildDownloadLogValuesCache($newDownloadLogId);
				
				$state->imported++;
			}
			
			if ($timer->limitExceeded())
			{
				break;
			}
		}
		
		return $state->resumeIfNeeded();
	}
	
	/**
	 * @param int $startAfter
	 * @param int $end
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function getDownloadLogs(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT downloadlog.*, license.purchase_id, license.license_url
			FROM xf_xr_pm_download AS
				downloadlog
			LEFT JOIN xf_xr_pm_product_purchase AS
				license USING(purchase_key)
			WHERE downloadlog.download_id > ? AND downloadlog.download_id <= ?
			ORDER BY downloadlog.download_id
			LIMIT {$limit}
		", 'download_id', [$startAfter, $end]);
	}
	
	
	
	
	// ############### UTILITY FUNCTIONS ##########################
	
	/**
	 * @param array $entry
	 *
	 * @return string|array|bool
	 */
	protected function decodePermissionEntry(array $entry)
	{
		switch ($entry['permission_id'])
		{
			case 'view':
				return 'view';

			case 'view_product_image':
				return 'viewProductAttach';

			case 'buy':
				return 'purchase';

			case 'use_coupons':
				return 'useCoupons';

			case 'use_coupons_limit':
			default:
				return false;
		}
	}
	
	/**
	 * @param string $visible
	 *
	 * @return string
	 */
	protected function decodeVisibleState(string $visible): string
	{
		switch ($visible)
		{
			case 0:
				return 'deleted';
			default:
				return 'visible';
		}
	}
	
	/**
	 * @param int $dataId
	 * @param string $filePath
	 * @param string $fileHash
	 *
	 * @return string
	 */
	protected function getSourceAttachmentDataPath(int $dataId, string $filePath, string $fileHash): string
	{
		$group = floor($dataId / 1000);
		
		if ($filePath)
		{
			$placeholders = [
				'%INTERNAL%' => 'internal-data://', // for legacy
				'%DATA%' => 'data://', // for legacy
				'%DATA_ID%' => $dataId,
				'%FLOOR%' => $group,
				'%HASH%' => $fileHash
			];
			$path = strtr($filePath, $placeholders);
			$path = str_replace(':///', '://', $path); // writing %INTERNAL%/path would cause this
		}
		else
		{
			$path = sprintf(
				'internal-data://attachments/%d/%d-%s.data',
				$group,
				$dataId,
				$fileHash
			);
		}
		
		return strtr($path, [
			'internal-data://' => $this->baseConfig['internal_data_dir'] . '/',
			'data://' => $this->baseConfig['data_dir'] . '/'
		]);
	}
	
	/**
	 * @param string $unit
	 *
	 * @return string
	 */
	protected function decodeCostUnit(string $unit): string
	{
		switch ($unit)
		{
			case 'days': return 'day';
			case 'years': return 'year';
			case 'months':
			default:
				return 'month';
		}
	}

	/**
	 * @param string $status
	 *
	 * @return string
	 */
	protected function decodeOrderState(string $status): string
	{
		switch (strtolower($status))
		{
			case 'active':
			case 'inactive':
			case 'expired':
			case 'revoked':
			case 'completed':
				return 'completed';
			case 'pending':
			default:
				return 'awaiting_payment';
		}
	}

	/**
	 * @param string $status
	 *
	 * @return string
	 */
	protected function decodeLicenseState(string $status): string
	{
		switch ($status)
		{
			case 'active':
			case 'complete':
			case 'expired':
			case 'inactive':
				return 'visible';

			case 'revoked':
				return 'deleted';

			case 'pending':
			default:
				return 'awaiting_payment';
		}
	}
	
	/**
	 * @param string $status
	 * @param int $licenseId
	 *
	 * @return string
	 */
	protected function decodeOrderItemState(string $status, int $licenseId = 0): string
	{
		switch ($status)
		{
			case 'cancel': return 'reversal';
			case 'payment':
			default:
				return $licenseId ? 'renew' : 'new';
		}
	}
}