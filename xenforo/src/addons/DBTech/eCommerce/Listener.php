<?php

namespace DBTech\eCommerce;

use XF\Container;

class Listener
{
	/**
	 * The product ID (in the DBTech store)
	 * @var int
	 */
	protected static $_productId = 371;
	
	/**
	 * @param \XF\App $app
	 *
	 * @throws \XF\Db\Exception
	 */
	public static function appSetup(\XF\App $app): void
	{
		$container = $app->container();
		
		$container['prefixes.dbtechEcommerceProduct'] = $app->fromRegistry(
			'dbtEcPrefixes',
			function (Container $c) { return $c['em']->getRepository('DBTech\eCommerce:ProductPrefix')->rebuildPrefixCache(); }
		);
		
		$container['customFields.dbtechEcommerceLicenses'] = $app->fromRegistry(
			'dbtEcLicenseFieldsInfo',
			function (Container $c) { return $c['em']->getRepository('DBTech\eCommerce:LicenseField')->rebuildFieldCache(); },
			function (array $fields) use ($app): \XF\CustomField\DefinitionSet
			{
				$class = 'XF\CustomField\DefinitionSet';
				$class = $app->extendClass($class);

				return new $class($fields);
			}
		);
		
		$container['customFields.dbtechEcommerceProducts'] = $app->fromRegistry(
			'dbtEcProductFieldsInfo',
			function (Container $c) { return $c['em']->getRepository('DBTech\eCommerce:ProductField')->rebuildFieldCache(); },
			function (array $fields) use ($app): \XF\CustomField\DefinitionSet
			{
				$class = 'XF\CustomField\DefinitionSet';
				$class = $app->extendClass($class);

				return new $class($fields);
			}
		);
		
		$container['customFields.dbtechEcommerceOrders'] = $app->fromRegistry(
			'dbtEcOrderFieldsInfo',
			function (Container $c) { return $c['em']->getRepository('DBTech\eCommerce:OrderField')->rebuildFieldCache(); },
			function (array $fields) use ($app): \XF\CustomField\DefinitionSet
			{
				$class = 'XF\CustomField\DefinitionSet';
				$class = $app->extendClass($class);

				return new $class($fields);
			}
		);

		$container['customFields.dbtechEcommerceReviews'] = $app->fromRegistry(
			'dbtEcReviewFieldsInfo',
			function (Container $c) { return $c['em']->getRepository('DBTech\eCommerce:ProductReviewField')->rebuildFieldCache(); },
			function (array $fields) use ($app): \XF\CustomField\DefinitionSet
			{
				$class = 'XF\CustomField\DefinitionSet';
				$class = $app->extendClass($class);

				return new $class($fields);
			}
		);
	}
	
	/**
	 * @param \XF\Pub\App $app
	 */
	public static function appPubSetup(\XF\Pub\App $app): void
	{
		/*DBTECH_BRANDING_START*/
		// Make sure we fetch the branding array from the application
		$branding = $app->offsetExists('dbtech_branding') ? $app->dbtech_branding : [];
		
		// Add productid to the array
		$branding[] = self::$_productId;
		
		// Store the branding
		$app->dbtech_branding = $branding;
		/*DBTECH_BRANDING_END*/
	}
	
	/**
	 * @param string $rule
	 * @param array $data
	 * @param \XF\Entity\User $user
	 * @param bool $returnValue
	 */
	public static function criteriaUser(string $rule, array $data, \XF\Entity\User $user, bool &$returnValue): void
	{
		switch ($rule)
		{
			case 'dbtech_ecommerce_has_pending_payment':
				$numOrders = \XF::finder('DBTech\eCommerce:Order')
					->where('order_state', 'awaiting_payment')
					->where('user_id', $user->user_id)
					->total();
				
				if ($numOrders)
				{
					$returnValue = true;
				}
				break;
			
			case 'dbtech_ecommerce_product_count':
				if (isset($user->dbtech_ecommerce_product_count) && $user->dbtech_ecommerce_product_count >= $data['products'])
				{
					$returnValue = true;
				}
				break;
			
			case 'not_dbtech_ecommerce_product_count':
				if (isset($user->dbtech_ecommerce_product_count) && $user->dbtech_ecommerce_product_count < $data['products'])
				{
					$returnValue = true;
				}
				break;
			
			case 'dbtech_ecommerce_license_count':
				if (isset($user->dbtech_ecommerce_license_count) && $user->dbtech_ecommerce_license_count >= $data['licenses'])
				{
					$returnValue = true;
				}
				break;
			
			case 'not_dbtech_ecommerce_license_count':
				if (isset($user->dbtech_ecommerce_license_count) && $user->dbtech_ecommerce_license_count < $data['licenses'])
				{
					$returnValue = true;
				}
				break;
			
			case 'dbtech_ecommerce_amount_spent':
				if (isset($user->dbtech_ecommerce_amount_spent) && $user->dbtech_ecommerce_amount_spent >= $data['amount'])
				{
					$returnValue = true;
				}
				break;
			
			case 'not_dbtech_ecommerce_amount_spent':
				if (isset($user->dbtech_ecommerce_amount_spent) && $user->dbtech_ecommerce_amount_spent < $data['amount'])
				{
					$returnValue = true;
				}
				break;
				
			case 'dbtech_ecommerce_products':
				/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
				$licenseRepo = \XF::repository('DBTech\eCommerce:License');
				
				if (isset($user->dbtech_ecommerce_license_count)
					&& $user->dbtech_ecommerce_license_count >= 1
					&& !empty($data['product_ids'])
					&& $licenseRepo->findLicensesByUserAndProduct($user->user_id, $data['product_ids'])->total() >= 1
				) {
					$returnValue = true;
				}
				break;
				
			case 'not_dbtech_ecommerce_products':
				/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
				$licenseRepo = \XF::repository('DBTech\eCommerce:License');
				
				if (!empty($data['product_ids'])
					&& !$licenseRepo->findLicensesByUserAndProduct($user->user_id, $data['product_ids'])->total()
				) {
					$returnValue = true;
				}
				break;
		}
	}

	/**
	 * @param $rule
	 * @param array $data
	 * @param \XF\Entity\User $user
	 * @param array $params
	 * @param $returnValue
	 *
	 * @return void
	 */
	public static function criteriaPage($rule, array $data, \XF\Entity\User $user, array $params, &$returnValue)
	{
		if ($rule === 'dbtech_ecommerce_categories')
		{
			$returnValue = false;

			if (!empty($data['category_ids']))
			{
				$selectedCategoryIds = $data['category_ids'];

				if (isset($params['breadcrumbs']) && is_array($params['breadcrumbs']) && empty($data['category_only']))
				{
					foreach ($params['breadcrumbs'] AS $i => $navItem)
					{
						if (
							isset($navItem['attributes']['category_id'])
							&& in_array($navItem['attributes']['category_id'], $selectedCategoryIds)
						) {
							$returnValue = true;
						}
					}
				}

				if (!empty($params['containerKey']))
				{
					[$type, $id] = explode('-', $params['containerKey'], 2);

					if ($type == 'dbtechEcommerceCategory' && $id && in_array($id, $selectedCategoryIds))
					{
						$returnValue = true;
					}
				}
			}
		}

		if ($rule === 'dbtech_ecommerce_products')
		{
			$returnValue = false;

			if (!empty($data['product_ids']))
			{
				$selectedProductIds = $data['product_ids'];

				if (isset($params['product'])
					&& ($params['product'] instanceof \DBTech\eCommerce\Entity\Product)
					&& \in_array($params['product']->product_id, $selectedProductIds)
				) {
					$returnValue = true;
				}
			}
		}

		if ($rule === 'dbtech_ecommerce_all_access')
		{
			$returnValue = false;

			if (isset($params['product'])
				&& ($params['product'] instanceof \DBTech\eCommerce\Entity\Product)
				&& $params['product']->is_all_access
			) {
				$returnValue = true;
			}
		}
	}

	/**
	 * @param array $templateData
	 */
	public static function criteriaTemplateData(array &$templateData): void
	{
		/** @var \DBTech\eCommerce\Repository\Product $productRepo */
		$productRepo = \XF::app()->repository('DBTech\eCommerce:Product');
		$templateData['dbtechEcommerceProducts'] = $productRepo->getProductsByCategory();

		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = \XF::app()->repository('DBTech\eCommerce:Category');
		$templateData['dbtechEcommerceCategories'] = $categoryRepo->getCategoryOptionsData(false);
	}

	/**
	 * @param \XF\Template\Templater $templater
	 * @param string $type
	 * @param string $template
	 * @param string $name
	 * @param array $arguments
	 * @param array $globalVars
	 */
	public static function templaterMacroPreRender(
		\XF\Template\Templater $templater,
		string &$type,
		string &$template,
		string &$name,
		array &$arguments,
		array &$globalVars
	): void {
		if (!empty($arguments['group']) && $arguments['group']->group_id == 'dbtechEcommerce')
		{
			// Override template name
			$template = 'dbtech_ecommerce_option_macros';
			
			// Or use 'option_form_block_tabs' for tabs
			$name = 'option_form_block';
			
			// Your block header configurations
			$arguments['headers'] = [
				'generalOptions'              => [
					'label'           => \XF::phrase('general_options'),
					'minDisplayOrder' => 0,
					'maxDisplayOrder' => 2000,
					'active'          => true
				],
				'paymentOptions'              => [
					'label'           => \XF::phrase('dbtech_ecommerce_payment_options'),
					'minDisplayOrder' => 2000,
					'maxDisplayOrder' => 3000
				],
				'invoiceOptions'              => [
					'label'           => \XF::phrase('dbtech_ecommerce_invoice_options'),
					'minDisplayOrder' => 3000,
					'maxDisplayOrder' => 4000
				],
				/*
				'purchaseConfirmationOptions' => [
					'label'           => \XF::phrase('dbtech_ecommerce_purchase_confirmation_options'),
					'minDisplayOrder' => 4000,
					'maxDisplayOrder' => 5000
				],
				*/
				'apiOptions'                  => [
					'label'           => \XF::phrase('dbtech_ecommerce_api_options'),
					'minDisplayOrder' => 5000,
					'maxDisplayOrder' => -1
				],
			];
		}
	}
	
	/**
	 * @param \XF\SubContainer\Import $container
	 * @param \XF\Container $parentContainer
	 * @param array $importers
	 */
	public static function importImporterClasses(
		\XF\SubContainer\Import $container,
		Container $parentContainer,
		array &$importers
	): void {
		$importers = array_merge(
			$importers,
			\XF\Import\Manager::getImporterShortNamesForType('DBTech/eCommerce')
		);
	}
	
	/**
	 * @param \XF\Service\User\ContentChange $changeService
	 * @param array $updates
	 */
	public static function userContentChangeInit(\XF\Service\User\ContentChange $changeService, array &$updates): void
	{
		$updates['xf_dbtech_ecommerce_address'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_category_watch'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_coupon_log'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_download_log'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_license'] = ['user_id', 'username'];
		$updates['xf_dbtech_ecommerce_order'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_order_item'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_product'] = ['user_id', 'username'];
		$updates['xf_dbtech_ecommerce_product_download'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_product_rating'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_product_watch'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_purchase_log'] = ['user_id', 'emptyable' => false];
		$updates['xf_dbtech_ecommerce_store_credit_log'] = ['user_id', 'emptyable' => false];
	}
	
	/**
	 * @param \XF\Service\User\DeleteCleanUp $deleteService
	 * @param array $deletes
	 */
	public static function userDeleteCleanInit(\XF\Service\User\DeleteCleanUp $deleteService, array &$deletes): void
	{
		$deletes['xf_dbtech_ecommerce_category_watch'] = 'user_id = ?';
		$deletes['xf_dbtech_ecommerce_coupon_log'] = 'user_id = ?';
		$deletes['xf_dbtech_ecommerce_license'] = 'user_id = ?';
		$deletes['xf_dbtech_ecommerce_product_download'] = 'user_id = ?';
		$deletes['xf_dbtech_ecommerce_product_rating'] = 'user_id = ?';
		$deletes['xf_dbtech_ecommerce_product_watch'] = 'user_id = ?';
		$deletes['xf_dbtech_ecommerce_purchase_log'] = 'user_id = ?';
		$deletes['xf_dbtech_ecommerce_store_credit_log'] = 'user_id = ?';
	}
	
	/**
	 * @param \XF\Entity\User $target
	 * @param \XF\Entity\User $source
	 * @param \XF\Service\User\Merge $mergeService
	 */
	public static function userMergeCombine(
		\XF\Entity\User $target,
		\XF\Entity\User $source,
		\XF\Service\User\Merge $mergeService
	): void {
		$target->dbtech_ecommerce_product_count += $source->dbtech_ecommerce_product_count;
		$target->dbtech_ecommerce_license_count += $source->dbtech_ecommerce_license_count;
		$target->dbtech_ecommerce_amount_spent += $source->dbtech_ecommerce_amount_spent;
	}
	
	/**
	 * @param \XF\Searcher\User $userSearcher
	 * @param array $sortOrders
	 */
	public static function userSearcherOrders(\XF\Searcher\User $userSearcher, array &$sortOrders): void
	{
		$sortOrders['dbtech_ecommerce_product_count'] = \XF::phrase('dbtech_ecommerce_product_count');
		$sortOrders['dbtech_ecommerce_license_count'] = \XF::phrase('dbtech_ecommerce_license_count');
		$sortOrders['dbtech_ecommerce_amount_spent'] = \XF::phrase('dbtech_ecommerce_amount_spent');
	}
	
	/**
	 * @param \XF\Container $container
	 * @param \XF\Template\Templater $templater
	 */
	public static function templaterSetup(Container $container, \XF\Template\Templater &$templater): void
	{
		
		
		$templater->addFunction('dbtech_ecommerce_product_icon', [__CLASS__, 'templaterFnProductIcon']);
		$templater->addFunction('dbtech_ecommerce_invoice_icon', [__CLASS__, 'templaterFnInvoiceIcon']);
	}
	
	/**
	 * @param \XF\Template\Templater $templater
	 * @param bool $escape
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param string $size
	 * @param string $href
	 * @param string $xfClick
	 *
	 * @return string
	 */
	public static function templaterFnProductIcon(
		\XF\Template\Templater $templater,
		bool &$escape,
		\DBTech\eCommerce\Entity\Product $product,
		string $size = 'm',
		string $href = '',
		string $xfClick = ''
	): string {
		$escape = false;
		
		if ($href)
		{
			$tag = 'a';
			$hrefAttr = 'href="' . htmlspecialchars($href) . '" data-xf-click="' . $xfClick . '"';
		}
		else
		{
			$tag = 'span';
			$hrefAttr = '';
		}
		
		if (!$product->icon_date)
		{
			return "<{$tag} {$hrefAttr} class=\"avatar avatar--{$size} avatar--productIconDefault\"><span></span></{$tag}>";
		}
		
		$src = $product->getIconUrl($size);
		
		return "<{$tag} {$hrefAttr} class=\"avatar avatar--{$size} avatar--productIcon\">"
			. '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($product->title) . '" loading="lazy" />'
			. "</{$tag}>";
	}
	
	/**
	 * @param \XF\Template\Templater $templater
	 * @param bool $escape
	 * @param string $href
	 * @param string $xfClick
	 *
	 * @return string
	 */
	public static function templaterFnInvoiceIcon(
		\XF\Template\Templater $templater,
		bool &$escape,
		string $href = '',
		string $xfClick = ''
	): string {
		$escape = false;
		
		if ($href)
		{
			$tag = 'a';
			$hrefAttr = 'href="' . htmlspecialchars($href) . '" data-xf-click="' . $xfClick . '"';
		}
		else
		{
			$tag = 'span';
			$hrefAttr = '';
		}
		
		if (!\XF::options()->dbtechEcommerceInvoiceIconDate)
		{
			return '';
		}
		
		$src = \XF::app()->applyExternalDataUrl(
			"dbtechEcommerce/invoiceIcons/" . \XF::options()->dbtechEcommerceInvoiceIconPath . '?' . \XF::options()->dbtechEcommerceInvoiceIconDate
		);
		
		return "<{$tag} {$hrefAttr}>"
			. '<img src="' . htmlspecialchars($src) . '" />'
			. "</{$tag}>";
	}
}