<?php

namespace DBTech\eCommerce\Import\Importer;

use XF\Import\StepState;

/**
 * Class XenProductManager
 *
 * @package DBTech\eCommerce\Import\Importer
 */
class XenProductManager extends AbstractCoreImporter
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
			'source' => 'Xen Product Manager (1.2.6+)',
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
				'host' => 'localhost',
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
	 *
	 */
	public function resetDataForRetainIds()
	{
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
			'products' => [
				'title' => \XF::phrase('dbtech_ecommerce_products')
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
			WHERE permission_group_id = 'xenproduct'
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
				// ASSUME user groups have not changed
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


	// ########################### STEP: PRODUCTS ###############################

	/**
	 * @return int
	 */
	public function getStepEndProducts(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(product_id)
			FROM xenproduct_product
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

		if (!isset($this->session->extra['xpm_optional_extras']))
		{
			$this->session->extra['xpm_optional_extras'] = [];
		}

		if (!isset($this->session->extra['xpm_imported_attachments']))
		{
			$this->session->extra['xpm_imported_attachments'] = [0];
		}

		foreach ($products AS $oldProductId => $product)
		{
			$state->startAfter = $oldProductId;

			$featureList = [];
			$features = \XF\Util\Php::safeUnserialize($product['features']);
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
				'creation_date' => 'product_date',
				'product_state',
				'user_id',
				'username',
			]));

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
				'copyright_info' 		=> '',
				'extra_group_ids' 		=> $product['user_group_id'] ? [$product['user_group_id']] : [],
				'parent_product_id' 	=> 0,
				'product_specification' => $featureList ? ("[LIST]\n[*]" . implode("\n[*]", $featureList) . "\n[/LIST]") : '',
				'product_category_id' 	=> 1,
				'license_prefix' 		=> 'L-XPM-' . $oldProductId,
				'is_discountable' 		=> 1,
				'is_paid' 				=> $product['price'] > 0.00,
				'has_demo' 				=> 0
			]);

			if ($product['product_state'] == 'deleted')
			{
				$import->setDeletionLogData([
					'delete_date'	=> $product['product_date'],
					'delete_user_id' => $product['user_id'],
					'delete_reason'  => 'Defunct product'
				]);
			}

			if ($newProductId = $import->save($oldProductId))
			{
				$extras = \XF\Util\Php::safeUnserialize($product['optional_extras']);
				if ($extras && is_array($extras))
				{
					foreach ($extras as $extra)
					{
						if (!isset($this->session->extra['xpm_optional_extras'][$extra]))
						{
							$this->session->extra['xpm_optional_extras'][$extra] = [];
						}

						$this->session->extra['xpm_optional_extras'][$extra][] = $newProductId;
					}
				}

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
					'length_unit'   => $this->decodeCostUnit($product['duration_unit'])
				]);

				$costImport->save(false);

				if ($product['product_thumbnail'] && $attachment = $this->sourceDb->fetchRow("
					SELECT a.*,
						ad.*
					FROM xf_attachment AS a
					INNER JOIN xf_attachment_data AS ad ON (a.data_id = ad.data_id)
					WHERE a.attachment_id = ?
						AND a.content_type = 'xenproduct_product'
				", $product['product_thumbnail']))
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

					$this->session->extra['xpm_imported_attachments'][] = $product['product_thumbnail'];
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
	protected function getProducts(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT product.*
			FROM xenproduct_product AS
				product
			WHERE product.product_id > ? AND product.product_id <= ?
			ORDER BY product.product_id
			LIMIT {$limit}
		", 'product_id', [$startAfter, $end]);
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
			WHERE attachment_id NOT IN(" . $this->sourceDb->quote($this->session->extra['xpm_imported_attachments']) . ")
				AND content_type = 'xenproduct_product'
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
				AND a.attachment_id NOT IN(" . $this->sourceDb->quote($this->session->extra['xpm_imported_attachments']) . ")
				AND a.content_type = 'xenproduct_product'
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
			FROM xenproduct_version
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
					'product_id'		   	=> $mappedProductId,
					'product_version'	  	=> $productVersion,
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
						AND a.content_type = 'xenproduct_version'
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
			FROM xenproduct_version AS
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
			FROM xenproduct_coupon
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
					'delete_date'	=> $coupon['coupon_valid_to'],
					'delete_user_id' => 0,
					'delete_reason'  => 'Defunct coupon'
				]);
			}

			if (
				$coupon['coupon_applies_to'] == 'product'
				&& $mappedProductId = $this->lookupId('product', $coupon['coupon_product_id']))
			{
				$import->addProduct($mappedProductId, 0);
			}

			if ($newCouponId = $import->save($oldCouponId))
			{
				$allowedUserGroups = \XF\Util\Php::safeUnserialize($coupon['coupon_usable_by']) ?: [];

				foreach ($groups as $group)
				{
					$userGroupId = $group['user_group_id'];

					$permHelper->insertContentUserGroupPermissions(
						'dbtech_ecommerce_coupon',
						$newCouponId,
						$userGroupId,
						['dbtechEcommerce' => ['useCoupons' => in_array($userGroupId, $allowedUserGroups) ? 'content_allow' : 'reset']]
					);
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
			FROM xenproduct_coupon AS
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
			FROM xenproduct_optional_extra
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

			if (empty($this->session->extra['xpm_optional_extras'][$oldAddOnId]))
			{
				$state->imported++;
				continue;
			}

			if (!isset($this->session->extra['xpm_optional_extras_map']))
			{
				$this->session->extra['xpm_optional_extras_map'] = [];
			}

			foreach ($this->session->extra['xpm_optional_extras'][$oldAddOnId] as $parentProductId)
			{
				/** @var \DBTech\eCommerce\Entity\Product $parentProduct */
				$parentProduct = $this->em()->find('DBTech\eCommerce:Product', $parentProductId);

				/** @var \DBTech\eCommerce\Import\Data\Product $import */
				$import = $this->newHandler('DBTech\eCommerce:Product');

				$import->setDescription('');
				$import->setTagline('');
				$import->addVersion('_any', 'Any');

				$import->bulkSet($this->mapXfKeys($addOn, [
					'title'				 => 'extra_title',
					'description_full'	  => 'extra_description',
				]));

				$import->bulkSet([
					'creation_date'		 => 'product_date',
					'product_state' 		=> $parentProduct->product_state,
					'user_id' 				=> $parentProduct->user_id,
					'username' 				=> $parentProduct->username,
					'copyright_info'	  	=> '',
					'extra_group_ids'	 	=> $addOn['extra_user_group_id'] ? [$addOn['extra_user_group_id']] : [],
					'parent_product_id'   	=> $parentProductId,
					'product_category_id' 	=> 1,
					'license_prefix'	  	=> 'L-XPM-ADDON-' . $parentProductId . '-' . $oldAddOnId,
					'is_discountable'		=> 1,
					'is_paid' 			  	=> $addOn['extra_price'] > 0.00,
					'has_demo'				=> 0
				]);

				if ($parentProduct->product_state == 'deleted')
				{
					$import->setDeletionLogData([
						'delete_date'	=> $parentProduct->creation_date,
						'delete_user_id' => $parentProduct->user_id,
						'delete_reason'  => 'Defunct add-on product'
					]);
				}

				if ($newAddOnId = $import->save(false))
				{
					if (!isset($this->session->extra['xpm_optional_extras_map'][$oldAddOnId]))
					{
						$this->session->extra['xpm_optional_extras_map'][$oldAddOnId] = [];
					}

					/** @var \DBTech\eCommerce\Import\Data\ProductCost $costImport */
					$costImport = $this->newHandler('DBTech\eCommerce:ProductCost');

					$costImport->bulkSet($this->mapXfKeys($addOn, [
						'cost_amount' => 'extra_price',
					]));

					$costImport->bulkSet([
						'product_id'	=> $newAddOnId,
						'creation_date' => $parentProduct->creation_date,
						'title' 		=> '',
						'stock' 		=> 0,
						'length_amount' => $parentProduct->Costs->first()->length_amount,
						'length_unit'   => $parentProduct->Costs->first()->length_unit,
					]);

					$newCostId = $costImport->save(false);

					$this->session->extra['xpm_optional_extras_map'][$oldAddOnId][$parentProductId] = [
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
							'product_id'		   	=> $parentProduct->product_id,
							'product_version'	  	=> '_any',
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
									AND a.content_type = 'xenproduct_optional_extra'
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
			FROM xenproduct_optional_extra AS
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
			SELECT MAX(cart_id)
			FROM xenproduct_cart
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

		foreach ($orders AS $oldOrderId => $order)
		{
			$state->startAfter = $oldOrderId;

			/** @var \DBTech\eCommerce\Import\Data\Order $import */
			$import = $this->newHandler('DBTech\eCommerce:Order');

			$import->bulkSet($this->mapXfKeys($order, [
				'user_id',
				'order_date' => 'update_date',
				'cost_amount' => 'total_cart_value',
			]));

			$import->bulkSet([
				'ip_address' => '::1',
				'order_state' => $this->decodeOrderState($order['cart_state']),
				'has_invoice' => 1,
				'sent_reminder' => 1,
				'store_credit_amount' => 0,
				'sub_total' => $order['total_cart_value'],
				'sale_discounts' => 0,
				'coupon_discounts' => 0,
				'automatic_discounts' => 0,
				'sales_tax' => 0,
				'taxable_order_total' => $order['total_cart_value'],
				'currency' => $order['cart_currency'],
				'extra_data' => [
					'sub_total' => $order['total_cart_value'],
					'sale_total' => 0,
					'coupon_discounts' => 0,
					'automatic_discounts' => 0,
					'sales_tax' => 0,
					'taxable_order_total' => $order['total_cart_value'],
					'cost_currency' => $order['cart_currency'],
				]
			]);

			if ($newOrderId = $import->save($oldOrderId))
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
	protected function getOrders(int $startAfter, int $end, int $limit): array
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT cart.*
			FROM xenproduct_cart AS
				cart
			WHERE cart.cart_id > ? AND cart.cart_id <= ?
			ORDER BY cart.cart_id
			LIMIT {$limit}
		", 'cart_id', [$startAfter, $end]);
	}


	// ########################### STEP: ORDER ITEMS ###############################

	/**
	 * @return int
	 */
	public function getStepEndOrderItems(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(item_id)
			FROM xenproduct_cart_item
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

		$this->typeMap('order');
		$this->typeMap('product');
		$this->typeMap('license');
		$this->typeMap('addon');

		/** @var \DBTech\eCommerce\Import\DataHelper\Order $orderHelper */
		$orderHelper = $this->dataManager->helper('DBTech\eCommerce:Order');

		foreach ($orderItems AS $oldOrderItemId => $orderItem)
		{
			$state->startAfter = $oldOrderItemId;

			if (!$mappedOrderId = $this->lookupId('order', $orderItem['cart_id']))
			{
				continue;
			}

			if (!$mappedProductId = $this->lookupId('product', $orderItem['product_id']))
			{
				continue;
			}

			/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
			$targetProduct = $this->em()->find('DBTech\eCommerce:Product', $mappedProductId);
			if (!$targetProduct)
			{
				continue;
			}

			$mappedLicenseId = $this->lookupId('license', $orderItem['renew_license_id'], 0);

			$orderHelper->importOrderItem($mappedOrderId, [
				'upgradetype'	   => $orderItem['is_renewal'] ? 'renew' : 'new',
				'user_id'		   => $orderItem['user_id'],
				'product_id'		=> $mappedProductId,
				'product_cost_id'   => $targetProduct->Costs->first()->product_cost_id,
				'shipping_method_id'=> 0,
				'license_id'		=> $mappedLicenseId,
				'coupon_id'		 => 0,
				'product_fields'	=> json_encode([]),
				'extra_data'		=> [
					'base_price' => $orderItem['unit_price'],
					'sale_discount' => 0,
					'coupon_discount' => 0,
					'sales_tax' => 0,
					'discounted_price' => $orderItem['unit_price'],
					'taxable_price' => $orderItem['unit_price'],
					'price' => $orderItem['unit_price'],
				]
			]);

			$this->em()->detachEntity($targetProduct);

			if ($orderItem['extras_total'] > 0.00)
			{
				$extras = \XF\Util\Php::safeUnserialize($orderItem['item_optional_extras']);
				foreach ($extras as $extra)
				{
					if (empty($this->session->extra['xpm_optional_extras_map'][$extra['extra_id']][$mappedProductId]))
					{
						continue;
					}

					$extraInfo = $this->session->extra['xpm_optional_extras_map'][$extra['extra_id']][$mappedProductId];

					$orderHelper->importOrderItem($mappedOrderId, [
						'upgradetype'	   => $orderItem['is_renewal'] ? 'renew' : 'new',
						'user_id'		   => $orderItem['user_id'],
						'product_id'		=> $extraInfo['product_id'],
						'product_cost_id'   => $extraInfo['product_cost_id'],
						'shipping_method_id'=> 0,
						'license_id'		=> $mappedLicenseId,
						'coupon_id'		 => 0,
						'product_fields'	=> json_encode([]),
						'extra_data'		=> [
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
			SELECT item.*, cart.user_id, cart.total_cart_value, cart.cart_currency
			FROM xenproduct_cart_item AS
				item
			LEFT JOIN xenproduct_cart AS cart USING(cart_id)
			WHERE item.item_id > ? AND item.item_id <= ?
			ORDER BY item.item_id
			LIMIT {$limit}
		", 'item_id', [$startAfter, $end]);
	}


	// ########################### STEP: TRANSACTIONS ###############################

	/**
	 * @return int
	 */
	public function getStepEndTransactions(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(transaction_log_id)
			FROM xenproduct_transaction_log
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

			if (!$mappedOrderId = $this->lookupId('order', $transaction['cart_id']))
			{
				continue;
			}

			/** @var \XF\Entity\PurchaseRequest $purchaseRequest */
			$purchaseRequest = $orderHelper->insertPurchaseRequest([
				'provider_id' => $transaction['processor'],
				'user_id' => $transaction['user_id'],
				'cost' => $transaction['total_cart_value'],
				'currency' => $transaction['cart_currency'],
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
				'transaction_id',
				'log_date',
				'subscriber_id',

				'provider_id' => 'processor',
				'log_type' => 'transaction_type',
				'log_message' => 'message',
				'log_details' => 'transaction_details'
			]));

			$import->purchase_request_key = $requestKey;

			if ($newTransactionId = $import->save(false))
			{
				if (in_array($transaction['transaction_type'], ['payment']))
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
								'user_id'	 		=> $item->user_id,
								'log_date'			=> $targetOrder->order_date,
								'cost_amount' 		=> $item->getPrice(),
								'log_type'			=> $this->decodeOrderItemState($transaction['transaction_type'], $item->license_id),
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
			SELECT transaction_log.*, cart.user_id, cart.total_cart_value, cart.cart_currency
			FROM xenproduct_transaction_log AS
				transaction_log
			LEFT JOIN xenproduct_cart AS cart USING(cart_id)
			WHERE transaction_log.transaction_log_id > ? AND transaction_log.transaction_log_id <= ?
			ORDER BY transaction_log.transaction_log_id
			LIMIT {$limit}
		", 'transaction_log_id', [$startAfter, $end]);
	}


	// ########################### STEP: LICENSES ###############################

	/**
	 * @return int
	 */
	public function getStepEndLicenses(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(license_id)
			FROM xenproduct_license
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
				'order_id' => $this->lookupId('order', $license['cart_id'], 0),
				'license_state' => 'visible',
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
						'field_value' => !empty($license['license_alias']) ? $license['license_alias'] : '',
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
			SELECT license.*, item.product_id, item.cart_id, cart.user_id, cart.username
			FROM xenproduct_license AS
				license
			LEFT JOIN xenproduct_cart_item
				AS item ON (item.item_id = license.item_id)
			LEFT JOIN xenproduct_cart
				AS cart ON (cart.cart_id = item.cart_id)
			WHERE license.license_id > ? AND license.license_id <= ?
			ORDER BY license.license_id
			LIMIT {$limit}
		", 'license_id', [$startAfter, $end]);
	}


	// ########################### STEP: LICENSES ###############################

	/**
	 * @return int
	 */
	public function getStepEndAddOnLicenses(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(license_id)
			FROM xenproduct_license
			WHERE license_optional_extras != 'a:0:{}'
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

			$extras = \XF\Util\Php::safeUnserialize($license['license_optional_extras']);
			foreach ($extras as $extra)
			{
				if (empty($this->session->extra['xpm_optional_extras_map'][$extra['extra_id']][$mappedProductId]))
				{
					continue;
				}

				$extraInfo = $this->session->extra['xpm_optional_extras_map'][$extra['extra_id']][$mappedProductId];

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
					'order_id' => $this->lookupId('order', $license['cart_id'], 0),
					'license_state' => 'visible',
					'latest_download_id' => 0,
					'parent_license_id' => $this->lookupId('license', $oldLicenseId, 0),
					'license_key' => $targetProduct->license_prefix  .
						$license['user_id'] .
						\strtoupper(\preg_replace('/[^A-Za-z0-9]/', 'A', \XF::generateRandomString(10)))
				]);

				if ($newLicenseId = $import->save(false))
				{
					$licenseHelper->importLicenseFieldValue($newLicenseId, [
						'field_id' => 'website',
						'field_value' => !empty($license['license_url']) ? $license['license_url'] : '',
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
			SELECT license.*, cart.user_id, cart.username, item.cart_id, item.product_id
			FROM xenproduct_license AS
				license
			LEFT JOIN xenproduct_cart_item
				AS item ON (item.item_id = license.item_id)
			LEFT JOIN xenproduct_cart
				AS cart ON (cart.cart_id = item.cart_id)
			WHERE license.license_id > ? AND license.license_id <= ?
				AND license.license_optional_extras != 'a:0:{}'
			ORDER BY license.license_id
			LIMIT {$limit}
		", 'license_id', [$startAfter, $end]);
	}


	// ########################### STEP: DOWNLOAD LOGS ###############################

	/**
	 * @return int
	 */
	public function getStepEndDownloadLogs(): int
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(download_id)
			FROM xenproduct_download
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

			if (!$mappedLicenseId = $this->lookupId('license', $downloadLog['license_id']))
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
			SELECT downloadlog.*, license.license_url
			FROM xenproduct_download AS
				downloadlog
			LEFT JOIN xenproduct_license AS
				license USING(license_id)
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
			case 'activateAny':
			case 'addCoupons':
			case 'addOptionalExtras':
			case 'additionalUserLimit':
			case 'convertCart':
			case 'couponLimit':
			case 'editLicense':
			case 'hardDeleteLicense':
			case 'hardDeleteOptionalExtra':
			case 'hardDeleteVersion':
			case 'updateLicenseAgreement':
			case 'viewDeleted':
			case 'viewLicenseAny':
			case 'viewLicensesAll':
			case 'viewOptionalExtras':
			case 'viewOrderAny':
			case 'viewOrdersAll':
			case 'activate':
			default:
				return false;

			case 'add':
				return ['add', 'uploadProductAttach', 'updateOwn', 'tagOwnProduct', 'manageOthersTagsOwnProd'];

			case 'addVersion':
				return 'updateOwn';

			case 'buy':
				return 'purchase';

			case 'changeCustomOrder':
				return 'assignCart';

			case 'delete':
				return 'deleteOwn';

			case 'hardDelete':
				return 'hardDeleteAny';

			case 'reassign':
				return 'reassign';

			case 'view':
				return 'view';

			case 'viewCoupons':
				return 'useCoupons';
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
		switch ($status)
		{
			case 'purchased':
				return 'completed';
			case 'expired':
				return 'reversed';
			case 'pending':
				return 'awaiting_payment';
			default:
				return 'pending';
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
			case 'cancel':
				return 'reversal';
			case 'payment':
			default:
				return $licenseId ? 'renew' : 'new';
		}
	}
}