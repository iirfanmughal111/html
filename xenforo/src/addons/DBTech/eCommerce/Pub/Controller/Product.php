<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\ParameterBag;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class Product extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		if (!\XF::options()->dbtechEcommerceAddressCountry)
		{
			throw $this->errorException(\XF::phrase('dbtech_ecommerce_setup_missing_business_details'));
		}
		
		switch ($action)
		{
			case 'LatestReviews':
			case 'Reviews':
			case 'Rate':
				if (!$this->options()->dbtechEcommerceEnableRate)
				{
					throw $this->exception($this->noPermission());
				}
				break;
		}
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Reroute|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionIndex(ParameterBag $params)
	{
		if ($params->product_id)
		{
			return $this->rerouteController(__CLASS__, 'view', $params);
		}
		
		/** @var \DBTech\eCommerce\ControllerPlugin\Overview $overviewPlugin */
		$overviewPlugin = $this->plugin('DBTech\eCommerce:Overview');
		
		$categoryParams = $overviewPlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();
		
		$listParams = $overviewPlugin->getCoreListData($viewableCategoryIds);
		
		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'dbtech-ecommerce');
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce', null, ['page' => $listParams['page']]));
		
		$viewParams = $categoryParams + $listParams;
		return $this->view('DBTech\eCommerce:Overview', 'dbtech_ecommerce_overview', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionView(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id, $this->getProductViewExtraWith());
		
		$license = $this->assertValidLicenseParameter($product);
		$licenseParams = [];
		if ($license)
		{
			$licenseParams['license_key'] = $license->license_key;
		}
		
		if ($product->isAddOn())
		{
			$product = $this->assertViewableProduct($product->parent_product_id);

			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce', $product, $licenseParams));
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('dbtech_ecommerce_product', $product->product_id);
		
		$currency = $this->options()->dbtechEcommerceCurrency;
		$productCosts = [];
		if ($product->is_paid)
		{
			foreach ($product->Costs as $cost)
			{
				/** @var \DBTech\eCommerce\Entity\ProductCost $cost */
				$productCosts[] = [
					'@type' => 'Offer',
					'price' => $cost->cost_amount,
					'priceCurrency' => $currency,
					'availability' => (!$product->hasStockFunctionality() ? 'InStock' : ($cost->stock ? 'InStock' : 'OutOfStock')),
					'url' => $product->getProductPageUrl()
				];
			}
		}
		
		$showCheckout = false;
		if ($this->options()->dbtechEcommerceEnableCheckoutProductPage)
		{
			/** @var \DBTech\eCommerce\Repository\Order $orderRepo */
			$orderRepo = $this->repository('DBTech\eCommerce:Order');
			
			if (\XF::visitor()->user_id)
			{
				/** @var \DBTech\eCommerce\Entity\Order $order */
				$order = $orderRepo->findCurrentOrderForUser()->fetchOne();
			}
			else
			{
				/** @var \DBTech\eCommerce\Entity\Order $order */
				$order = $orderRepo->findCurrentOrderForGuest()->fetchOne();
			}
			
			if ($order)
			{
				/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
				foreach ($order->Items as $orderItem)
				{
					if ($orderItem->product_id == $product->product_id)
					{
						$showCheckout = true;
					}
				}
			}
		}
		
		$viewParams = [
			'product' => $product,
			'category' => $product->Category,
			'productCosts' => $productCosts,
			'showCheckout' => $showCheckout,
			//			'latestUpdates' => $latestUpdates,
			//			'latestReviews' => $latestReviews,
			//			'authorOthers' => $authorOthers,

			'license' => $license
		];
		return $this->view('DBTech\eCommerce:Product\View', 'dbtech_ecommerce_product_view', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionSpecifications(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id, $this->getProductViewExtraWith());
		
		$license = $this->assertValidLicenseParameter($product);
		$licenseParams = [];
		if ($license)
		{
			$licenseParams['license_key'] = $license->license_key;
		}
		
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/specifications', $product, $licenseParams));
		
		$viewParams = [
			'product' => $product,

			'license' => $license
		];
		return $this->view('DBTech\eCommerce:Product\Features', 'dbtech_ecommerce_product_specifications', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionCopyright(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id, $this->getProductViewExtraWith());
		
		$license = $this->assertValidLicenseParameter($product);
		$licenseParams = [];
		if ($license)
		{
			$licenseParams['license_key'] = $license->license_key;
		}
		
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/copyright', $product, $licenseParams));
		
		$viewParams = [
			'product' => $product,

			'license' => $license
		];
		return $this->view('DBTech\eCommerce:Product\Features', 'dbtech_ecommerce_product_copyright', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionField(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		$fieldId = $this->filter('field', 'str');
		$tabFields = $product->getExtraFieldTabs();
		
		if (!isset($tabFields[$fieldId]))
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $product->product_fields;
		$definition = $fieldSet->getDefinition($fieldId);
		$fieldValue = $fieldSet->getFieldValue($fieldId);
		
		$viewParams = [
			'product' => $product,
			'category' => $product->Category,
			
			'fieldId' => $fieldId,
			'fieldDefinition' => $definition,
			'fieldValue' => $fieldValue
		];
		return $this->view('DBTech\eCommerce:Product\Field', 'dbtech_ecommerce_product_field', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionReleases(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		if (!$product->hasDownloadFunctionality())
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$license = $this->assertValidLicenseParameter($product);
		$licenseParams = [];
		if ($license)
		{
			$licenseParams['license_key'] = $license->license_key;
		}
		
		$total = $product->real_release_count;
		if (!$total)
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product, $licenseParams));
		}
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceDownloadsPerPage;
		
		$this->assertValidPage($page, $perPage, $total, 'dbtech-ecommerce/releases', $product);
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/releases', $product, $licenseParams + ['page' => $page]));
		
		$visitor = \XF::visitor();
		
		/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
		$downloadRepo = $this->repository('DBTech\eCommerce:Download');
		$downloadFinder = $downloadRepo->findDownloadsInProduct($product)->with([
			'Discussion',
			'Discussion.Forum',
			'Discussion.Forum.Node',
			'Discussion.Forum.Node.Permissions|' . $visitor->permission_combination_id,
		]);
		
		$downloadFinder->limitByPage($page, $perPage);
		$downloads = $downloadFinder->fetch()->filterViewable();
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('dbtech_ecommerce_product', $product->product_id);
		$userAlertRepo->markUserAlertsReadForContent('dbtech_ecommerce_download', $downloads->keys());
		
		$hasDownload = $hasDiscussion = false;
		
		/** @var \DBTech\eCommerce\Entity\Download $download */
		foreach ($downloads AS $download)
		{
			if ($download->canDownload($license))
			{
				$hasDownload = true;
			}
			
			if ($download->hasViewableDiscussion())
			{
				$hasDiscussion = true;
			}
		}

		/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
		$downloadRepo = $this->repository('DBTech\eCommerce:Download');
		$groupedVersions = $downloadRepo->findDownloadVersionsInProduct($product)
										->where('product_version_type', 'full')
										->fetch()
										->groupBy('download_id');
		foreach ($groupedVersions as $downloadId => $versions)
		{
			/** @var \DBTech\eCommerce\Entity\Download $download */
			$download = $downloads[$downloadId] ?? null;
			if ($download)
			{
				$download->hydrateRelation('Versions', new ArrayCollection($versions));
				$download->hydrateRelation('FullVersions', new ArrayCollection($download->Versions->groupBy('product_version')));
			}
		}

		$viewParams = [
			'product' => $product,
			'downloads' => $downloads,
			'hasDownload' => $hasDownload,
			'hasDiscussion' => $hasDiscussion,
			
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			
			'license' => $license,
			'licenseParams' => $licenseParams,
			
			'_noWrap' => $this->request->isXhr()
		];
		return $this->view('DBTech\eCommerce:Product\History', 'dbtech_ecommerce_product_releases', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionReleasesDownload(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		if (!$product->hasDownloadFunctionality())
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$license = $this->assertValidLicenseParameter($product);
		if (!$license)
		{
			throw $this->exception($this->noPermission());
		}
		
		if (!$license->hasValidLicenseFields())
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_missing_required_license_info'));
		}
		
		/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
		$downloadRepo = $this->repository('DBTech\eCommerce:Download');
		$downloadFinder = $downloadRepo->findDownloadsInProduct($product);
		$downloads = $downloadFinder->fetch()
			->filter(function (\DBTech\eCommerce\Entity\Download $download) use ($license): bool
			{
				return $download->isVisible() && $download->canDownload($license);
			})
		;

		$attachmentVersions = [];
		$groupedVersions = $downloadRepo->findDownloadVersionsInProduct($product)
										->fetch()
										->groupBy('download_id');
		foreach ($groupedVersions as $downloadId => $versions)
		{
			/** @var \DBTech\eCommerce\Entity\Download $download */
			$download = $downloads[$downloadId] ?? null;
			if ($download)
			{
				$download->hydrateRelation('Versions', new ArrayCollection($versions));
				$download->hydrateRelation('FullVersions', new ArrayCollection($download->Versions->groupBy('product_version')));
				/** @var \DBTech\eCommerce\Entity\DownloadVersion $version */
				foreach ($versions as $version)
				{
					if ($version->attach_count > 0)
					{
						$attachmentVersions[$version->download_version_id] = $version;
					}
				}
			}
		}
		if ($attachmentVersions)
		{
			$attachments = $this->finder('XF:Attachment')
								->with('Data', true)
								->where('content_type', 'dbtech_ecommerce_version')
								->where('content_id', array_keys($attachmentVersions))
								->order('attach_date')
								->fetch()
								->groupBy('content_id');
			foreach ($attachments as $contentId => $versionAttachments)
			{
				$version = $attachmentVersions[$contentId] ?? null;
				if ($version)
				{
					$version->hydrateRelation('Attachments', new ArrayCollection($versionAttachments));
				}
			}
		}

		$viewParams = [
			'product' => $product,
			'downloads' => $downloads,
			
			'license' => $license,
			
			'_noWrap' => $this->request->isXhr()
		];
		return $this->view('DBTech\eCommerce:Product\Releases', 'dbtech_ecommerce_product_releases_download_chooser', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionLicenses(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		if (!$product->hasLicenseFunctionality())
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$license = $this->assertValidLicenseParameter($product);
		$licenseParams = [];
		if ($license)
		{
			$licenseParams['license_key'] = $license->license_key;
		}
		
		$licenses = $product->UserLicenses;
		if (!$licenses)
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product, $licenseParams));
		}
		
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/product-licenses', $product, $licenseParams));
		
		$viewParams = [
			'product' => $product,
			'licenses' => $product->UserLicenses,
			
			'license' => $license,
			'licenseParams' => $licenseParams,
			
			'_noWrap' => $this->request->isXhr()
		];
		return $this->view('DBTech\eCommerce:Product\Licenses', 'dbtech_ecommerce_product_licenses', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionEditIcon(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canEditIcon($error))
		{
			return $this->noPermission($error);
		}
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Product\Icon $iconService */
			$iconService = $this->service('DBTech\eCommerce:Product\Icon', $product);
			
			$action = $this->filter('icon_action', 'str');
			
			if ($action == 'delete')
			{
				$iconService->deleteIcon();
			}
			elseif ($action == 'custom')
			{
				$upload = $this->request->getFile('upload', false, false);
				if ($upload)
				{
					if (!$iconService->setImageFromUpload($upload))
					{
						return $this->error($iconService->getError());
					}
					
					if (!$iconService->updateIcon())
					{
						return $this->error(\XF::phrase('dbtech_ecommerce_new_icon_could_not_be_applied_try_later'));
					}
				}
			}
			
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$viewParams = [
			'product' => $product,
		];
		return $this->view('DBTech\eCommerce:Product\EditIcon', 'dbtech_ecommerce_product_edit_icon', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionPurchase(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);

		$license = $this->assertValidLicenseParameter($product);
		$licenseParams = [];
		if ($license)
		{
			$licenseParams['license_key'] = $license->license_key;
		}

		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/purchase', $product, $licenseParams));

		if (!$product->canView($error))
		{
			return $this->noPermission($error);
		}

		if (!$product->canPurchase($license, $error))
		{
			return $this->noPermission($error);
		}

		$redirect = $this->getDynamicRedirect(null, false);

		$purchasedAddOns = [];
		if ($product->hasAddonFunctionality() && $product->hasLicenseFunctionality() && $license)
		{
			/** @var \DBTech\eCommerce\Entity\License $childLicense */
			foreach ($license->Children as $childLicense)
			{
				$purchasedAddOns[$childLicense->product_id] = true;
			}
		}

		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Order\Creator $creator */
			$creator = $this->service('DBTech\eCommerce:Order\Creator');

			$pricingTier = $this->filter('pricing_tier', 'uint');
			$quantity = $this->filter('quantity', 'uint', 1);
			$addOns = $this->filter('addon_pricing_tier', 'array-uint');

			if (!$product->hasQuantityFunctionality())
			{
				// Force quantity to 1 when quantity isn't supported
				$quantity = 1;
			}

			if (empty($pricingTier) || !isset($product->Costs[$pricingTier]))
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_selected_pricing_tier_invalid'));
			}

			if ($product->hasStockFunctionality())
			{
				if ($product->Costs[$pricingTier]->stock <= 0)
				{
					return $this->error(\XF::phrase('dbtech_ecommerce_selected_variation_out_of_stock'));
				}
				elseif ($product->Costs[$pricingTier]->stock < $quantity)
				{
					return $this->error(\XF::phrase('dbtech_ecommerce_selected_variation_only_has_x_in_stock', [
						'available' => \XF::language()->numberFormat($product->Costs[$pricingTier]->stock)
					]));
				}
			}

			$creator->addItem(
				$product,
				$product->Costs[$pricingTier],
				$license,
				null,
				$quantity
			);

			if ($addOns)
			{
				foreach ($addOns as $childProductId => $addOnPricingTier)
				{
					if (
						!empty($purchasedAddOns[$childProductId])
						|| !isset($product->Children[$childProductId], $product->Children[$childProductId]->Costs[$addOnPricingTier])
						|| !$product->Children[$childProductId]->canPurchase()
					) {
						return $this->error(\XF::phrase('dbtech_ecommerce_invalid_add_on_configuration'));
					}

					$creator->addItem(
						$product->Children[$childProductId],
						$product->Children[$childProductId]->Costs[$addOnPricingTier],
						null,
						$license,
						$quantity
					);
				}
			}

			if (!$creator->validate($errors))
			{
				return $this->error($errors);
			}

			$creator->save();

			return $this->redirect($redirect, \XF::phrase('dbtech_ecommerce_items_added_to_cart'));
		}

		$selectedCost = $product->Costs->first();
		foreach ($product->Costs as $cost)
		{
			if ($cost->highlighted)
			{
				$selectedCost = $cost;
				break;
			}
		}

		$viewParams = [
			'product' => $product,
			'redirect' => $redirect,

			'selectedCost' => $selectedCost,

			'license' => $license,
			'purchasedAddOns' => $purchasedAddOns
		];
		return $this->view('DBTech\eCommerce:Product\Purchase', 'dbtech_ecommerce_product_purchase', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionPurchaseAllAccess(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$visitor = \XF::visitor();
		$product = $this->assertViewableProduct($params->product_id);

		$license = $this->assertValidLicenseParameter($product);
		$licenseParams = [];
		if ($license)
		{
			return $this->noPermission();
		}

		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/purchase/all-access', $product, $licenseParams));

		if (!$product->canView($error))
		{
			return $this->noPermission($error);
		}

		if (!$product->canPurchaseAllAccess($license, $error))
		{
			return $this->noPermission($error);
		}

		/** @var \DBTech\eCommerce\Service\License\Create $creator */
		$creator = \XF::app()->service('DBTech\eCommerce:License\Create', $product);
		$creator->getLicense()->bulkSet([
			'required_user_group_ids' => $product->all_access_group_ids ?: []
		]);
		$creator->setDuration('permanent', 0, '');

		if (!$creator->validate($errors))
		{
			return $this->error($errors);
		}

		$creator->save();

		return $this->redirect(
			$this->buildLink('dbtech-ecommerce/licenses', $visitor),
			\XF::phrase('dbtech_ecommerce_license_added')
		);
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionPurchaseAddOns(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id, $this->getProductViewExtraWith());
		
		$license = $this->assertValidLicenseParameter($product, ['User']);
		if ($product->hasLicenseFunctionality() && !$license)
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_invalid_license'));
		}
		
		$licenseParams = [];
		if ($license)
		{
			$licenseParams['license_key'] = $license->license_key;
		}
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/purchase/add-ons', $product, $licenseParams));
		
		if (!$product->canView($error))
		{
			return $this->noPermission($error);
		}
		
		if (!$product->canPurchase(null, $error))
		{
			return $this->noPermission($error);
		}
		
		if (!count($product->Children))
		{
			return $this->noPermission();
		}
		
		$redirect = $this->getDynamicRedirect(null, false);
		
		$purchasedAddOns = [];
		if ($product->hasLicenseFunctionality())
		{
			/** @var \DBTech\eCommerce\Entity\License $childLicense */
			foreach ($license->Children as $childLicense)
			{
				$purchasedAddOns[$childLicense->product_id] = true;
			}
		}
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Order\Creator $creator */
			$creator = $this->service('DBTech\eCommerce:Order\Creator');
			
			if ($product->hasLicenseFunctionality())
			{
				$addOns = $this->filter('addon_pricing_tier', 'array-uint');
				if ($addOns)
				{
					foreach ($addOns as $childProductId => $addOnPricingTier)
					{
						if (
							!empty($purchasedAddOns[$childProductId])
							|| !isset($product->Children[$childProductId], $product->Children[$childProductId]->Costs[$addOnPricingTier])
							|| !$product->Children[$childProductId]->canPurchase()
						) {
							return $this->error(\XF::phrase('dbtech_ecommerce_invalid_add_on_configuration'));
						}
						
						$creator->addItem(
							$product->Children[$childProductId],
							$product->Children[$childProductId]->Costs[$addOnPricingTier],
							null,
							$license
						);
					}
				}
			}
			
			// @TODO: Physical items
			
			if (!$creator->validate($errors))
			{
				return $this->error($errors);
			}
			
			$creator->save();
			
			return $this->redirect($redirect, \XF::phrase('dbtech_ecommerce_items_added_to_cart'));
		}
		
		$viewParams = [
			'product' => $product,
			'redirect' => $redirect,
			
			'license' => $license,
			'purchasedAddOns' => $purchasedAddOns
		];
		return $this->view('DBTech\eCommerce:Product\AddOn\Purchase', 'dbtech_ecommerce_product_addon_purchase', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \Exception
	 * @throws \Exception
	 */
	public function actionFilters()
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\Overview $overviewPlugin */
		$overviewPlugin = $this->plugin('DBTech\eCommerce:Overview');
		
		return $overviewPlugin->actionFilters();
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionPrefixes(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$this->assertPostOnly();

		$categoryId = $this->filter('val', 'uint');

		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->em()->find(
			'DBTech\eCommerce:Category',
			$categoryId,
			'Permissions|' . \XF::visitor()->permission_combination_id
		);
		if (!$category)
		{
			return $this->notFound(\XF::phrase('requested_category_not_found'));
		}

		if (!$category->canView($error))
		{
			return $this->noPermission($error);
		}

		$viewParams = [
			'category' => $category,
			'prefixes' => $category->getUsablePrefixes()
		];
		return $this->view('DBTech\eCommerce:Category\Prefixes', 'dbtech_ecommerce_category_prefixes', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionWatch(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canWatch($error))
		{
			return $this->noPermission($error);
		}
		
		$visitor = \XF::visitor();
		
		if ($this->isPost())
		{
			if ($this->filter('stop', 'bool'))
			{
				$action = 'delete';
				$config = [];
			}
			else
			{
				$action = 'watch';
				$config = [
					'email_subscribe' => $this->filter('email_subscribe', 'bool')
				];
			}
			
			/** @var \DBTech\eCommerce\Repository\ProductWatch $watchRepo */
			$watchRepo = $this->repository('DBTech\eCommerce:ProductWatch');
			$watchRepo->setWatchState($product, $visitor, $action, $config);
			
			$redirect = $this->redirect($this->buildLink('dbtech-ecommerce', $product));
			$redirect->setJsonParam('switchKey', $action == 'delete' ? 'watch' : 'unwatch');
			return $redirect;
		}
		
		$viewParams = [
			'product' => $product,
			'isWatched' => !empty($product->Watch[$visitor->user_id]),
			'category' => $product->Category
		];
		return $this->view('DBTech\eCommerce:Product\Watch', 'dbtech_ecommerce_product_watch', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionRate(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$visitorUserId = \XF::visitor()->user_id;
		
		$extraWith = [
			'ProductDownloads|' . $visitorUserId,
			'Ratings|' . $visitorUserId,
		];
		$product = $this->assertViewableProduct($params->product_id, $extraWith);
		if (!$product->canRate(true, $error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \DBTech\eCommerce\Entity\ProductRating|null $existingRating */
		$existingRating = $product->Ratings[$visitorUserId];
		if ($existingRating && !$existingRating->canUpdate($error))
		{
			return $this->noPermission($error);
		}
		
		if ($this->isPost())
		{
			$rater = $this->setupProductRate($product);
			$rater->checkForSpam();
			
			if (!$rater->validate($errors))
			{
				return $this->error($errors);
			}
			
			$rating = $rater->save();
			
			return $this->redirect($this->buildLink(
				$rating->is_review ? 'dbtech-ecommerce/reviews' : 'dbtech-ecommerce',
				$product
			));
		}

		/** @var \DBTech\eCommerce\Entity\ProductRating $rating */
		$rating = $this->em()->create('DBTech\eCommerce:ProductRating');

		$viewParams = [
			'product' => $product,
			'category' => $product->Category,
			'rating' => $rating,
			'existingRating' => $existingRating
		];
		return $this->view('DBTech\eCommerce:Product\Rate', 'dbtech_ecommerce_product_rate', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\Product\Rate
	 */
	protected function setupProductRate(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\Product\Rate
	{
		/** @var \DBTech\eCommerce\Service\Product\Rate $rater */
		$rater = $this->service('DBTech\eCommerce:Product\Rate', $product);
		
		$input = $this->filter([
			'rating' => 'uint',
			'message' => 'str',
			'is_anonymous' => 'bool'
		]);

		$customFields = $this->filter('custom_fields', 'array');
		$rater->setCustomFields($customFields);

		$hasCustomFieldValues = false;
		foreach ($rater->getRating()->custom_fields AS $field => $value)
		{
			if (is_array($value))
			{
				if (count($value) > 0)
				{
					$hasCustomFieldValues = true;
				}
			}
			elseif (trim(strval($value)) !== '')
			{
				$hasCustomFieldValues = true;
			}
		}

		if ($hasCustomFieldValues && !strlen($input['message']))
		{
			$input['message'] = \XF::phrase('dbtech_ecommerce_no_review_provided')->render();
		}
		
		$rater->setRating($input['rating'], $input['message']);
		
		if ($this->options()->dbtechEcommerceAllowAnonReview && $input['is_anonymous'])
		{
			$rater->setIsAnonymous();
		}
		
		return $rater;
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionLatestReviews(): \XF\Mvc\Reply\AbstractReply
	{
		$viewableCategoryIds = $this->getCategoryRepo()->getViewableCategoryIds();
		
		/** @var \DBTech\eCommerce\Repository\ProductRating $ratingRepo */
		$ratingRepo = $this->repository('DBTech\eCommerce:ProductRating');
		$finder = $ratingRepo->findLatestReviews($viewableCategoryIds);
		
		$total = $finder->total();
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceReviewsPerPage;
		
		$this->assertValidPage($page, $perPage, $total, 'dbtech-ecommerce/latest-reviews');
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/latest-reviews', null, ['page' => $page]));
		
		$reviews = $finder->limitByPage($page, $perPage)->fetch();
		$reviews = $reviews->filterViewable();
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('dbtech_ecommerce_rating', $reviews->keys());
		
		$viewParams = [
			'reviews' => $reviews,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total
		];
		return $this->view('DBTech\eCommerce:LatestReviews', 'dbtech_ecommerce_latest_reviews', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionReviews(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if (!$params->product_id)
		{
			return $this->redirectPermanently($this->buildLink('dbtech-ecommerce/latest-reviews'));
		}
		
		$product = $this->assertViewableProduct($params->product_id);
		
		$license = $this->assertValidLicenseParameter($product);
		$licenseParams = [];
		if ($license)
		{
			$licenseParams['license_key'] = $license->license_key;
		}
		
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/reviews', $product, $licenseParams));
		
		$reviewId = $this->filter('product_rating_id', 'uint');
		if ($reviewId)
		{
			/** @var \DBTech\eCommerce\Entity\ProductRating|null $review */
			$review = $this->em()->find('DBTech\eCommerce:ProductRating', $reviewId);
			if (!$review || $review->product_id != $product->product_id || !$review->is_review)
			{
				return $this->noPermission();
			}
			if (!$review->canView($error))
			{
				return $this->noPermission($error);
			}
			
			return $this->redirectPermanently($this->buildLink('dbtech-ecommerce/review', $review));
		}
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceReviewsPerPage;
		
		/** @var \DBTech\eCommerce\Repository\ProductRating $ratingRepo */
		$ratingRepo = $this->repository('DBTech\eCommerce:ProductRating');
		$reviewFinder = $ratingRepo->findReviewsInProduct($product);
		
		$total = $product->real_review_count;
		if (!$total)
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$this->assertValidPage($page, $perPage, $total, 'dbtech-ecommerce/reviews', $product);
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/reviews', $product, ['page' => $page]));
		
		$reviewFinder->with('full')->limitByPage($page, $perPage);
		$reviews = $reviewFinder->fetch();
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('dbtech_ecommerce_rating', $reviews->keys());
		
		$viewParams = [
			'product' => $product,
			'reviews' => $reviews,
			
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,

			'license' => $license
		];
		return $this->view('DBTech\eCommerce:Product\Reviews', 'dbtech_ecommerce_product_reviews', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionTags(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canEditTags($error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \XF\Service\Tag\Changer $tagger */
		$tagger = $this->service('XF:Tag\Changer', 'dbtech_ecommerce_product', $product);
		
		if ($this->isPost())
		{
			$tagger->setEditableTags($this->filter('tags', 'str'));
			if ($tagger->hasErrors())
			{
				return $this->error($tagger->getErrors());
			}
			
			$tagger->save();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$grouped = $tagger->getExistingTagsByEditability();
		
		$viewParams = [
			'product' => $product,
			'category' => $product->Category,
			'editableTags' => $grouped['editable'],
			'uneditableTags' => $grouped['uneditable']
		];
		return $this->view('DBTech\eCommerce:Product\Tags', 'dbtech_ecommerce_product_tags', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 */
	protected function productAddEdit(\DBTech\eCommerce\Entity\Product $product): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->em()->find('DBTech\eCommerce:Category', $product->product_category_id);
		
		if ($category && $category->canUploadAndManageProductImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('dbtech_ecommerce_product', $product->exists() ? $product : $product->Category);
		}
		else
		{
			$attachmentData = null;
		}
		
		/** @var \DBTech\eCommerce\Repository\OrderField $fieldRepo */
		$fieldRepo = $this->repository('DBTech\eCommerce:OrderField');
		$availableFields = $fieldRepo->findFieldsForList()->fetch();
		
		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');
		
		if ($product->ThreadForum)
		{
			$threadPrefixes = $product->ThreadForum->getPrefixesGrouped();
		}
		else
		{
			$threadPrefixes = [];
		}
		
		if ($product->exists() && $product->canEditTags())
		{
			/** @var \XF\Service\Tag\Changer $tagger */
			$tagger = $this->service('XF:Tag\Changer', 'dbtech_ecommerce_product', $product);
			
			$grouped = $tagger->getExistingTagsByEditability();
		}
		else
		{
			$grouped = [
				'editable' => null,
				'uneditable' => null,
			];
		}
		
		$viewParams = [
			'product' => $product,
			'category' => $category,
			
			'forumOptions' => $nodeRepo->getNodeOptionsData(false, 'Forum'),
			'threadPrefixes' => $threadPrefixes,
			
			'attachmentData' => $attachmentData,
			'prefixes' => $category->getUsablePrefixes($product->prefix_id),
			
			'editableTags' => $grouped['editable'],
			'uneditableTags' => $grouped['uneditable'],
			
			'availableFields' => $availableFields,
			
			'productOwner' => $product->exists() ? $product->User : $this->em()->find('XF:User', $this->options()->dbtechEcommerceDefaultProductOwner),
		];
		return $this->view('DBTech\eCommerce:Product\Edit', 'dbtech_ecommerce_product_edit', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canEdit($error))
		{
			return $this->noPermission($error);
		}
		
		return $this->productAddEdit($product);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 *
	 * @return \DBTech\eCommerce\Service\Product\Create
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupProductCreate(\DBTech\eCommerce\Entity\Category $category): \DBTech\eCommerce\Service\Product\Create
	{
		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$fullDescription = $editorPlugin->fromInput('description_full');
		$specification = $editorPlugin->fromInput('product_specification');
		$copyright = $editorPlugin->fromInput('copyright_info');
		
		/** @var \DBTech\eCommerce\Service\Product\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Product\Create', $category, $this->filter('product_type', 'str'));
		$creator->setPerformValidations(false);
		
		$product = $creator->getProduct();
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'is_featured' => 'bool',
			'is_discountable' => 'bool',
			'is_listed' => 'bool',
			'welcome_email' => 'bool',
			'is_all_access' => 'bool',
			'all_access_group_ids' => 'array-uint',
			'license_prefix' => 'str',
			'product_type_data' => 'array',
			'has_demo' => 'bool',
			'extra_group_ids' => 'array-uint',
			'temporary_extra_group_ids' => 'array-uint',
			'support_node_id' => 'uint',
			'thread_node_id' => 'uint',
			'thread_prefix_id' => 'uint',
			'branding_free' => 'uint',
			'global_branding_free' => 'uint',
		]);
		$product->bulkSet($bulkInput);
		
		$creator->setParentProduct($this->filter('parent_product_id', 'uint'));
		
		$creator->setTagLine($this->filter('tagline', 'str'));
		$creator->setDescription($this->filter('description', 'str'));
		
		$creator->setContent($fullDescription, $specification, $copyright);
		
		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId && $category->isPrefixUsable($prefixId))
		{
			$creator->setPrefix($prefixId);
		}
		
		if ($category->canEditTags())
		{
			$creator->setTags($this->filter('tags', 'str'));
		}
		
		$creator->setRequirements($this->filter('requirements', 'str'));
		
		if (!$product->isAddOn())
		{
			$creator->setVersions(
				$this->filter('product_version', 'array-str'),
				$this->filter('product_version_text', 'array-str')
			);
		}
		
		if ($category->canUploadAndManageProductImages())
		{
			$creator->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}

		$filterIds = $this->filter('available_filters', 'array-str');
		$creator->setAvailableFilters($filterIds);

		$fieldIds = $this->filter('available_fields', 'array-str');
		$creator->setAvailableFields($fieldIds);

		$welcomeEmail = $this->filter('welcome_email_options', 'array');
		$creator->setWelcomeEmail($welcomeEmail);
		
		$costInput = $this->filter([
			'cost_title' => 'array-str',
			'cost_amount' => 'array-float',
			'renewal_type' => 'array-str',
			'renewal_amount' => 'array-float',
			'length_type' => 'array-str',
			'length_amount' => 'array-str',
			'length_unit' => 'array-str',
			'stock' => 'array-uint',
			'weight' => 'array-float',
			'cost_description' => 'array-str',
			'highlighted' => 'uint'
		]);
		$costIds = array_keys($costInput['cost_amount']);
		
		foreach ($costIds as $costId)
		{
			if ($product->hasLicenseFunctionality())
			{
				$creator->addProductCost($costId, [
					'title' => $costInput['cost_title'][$costId] ?? '',
					'cost_amount' => $costInput['cost_amount'][$costId],
					'renewal_type' => $costInput['renewal_type'][$costId] ?? null,
					'renewal_amount' => isset($costInput['renewal_type'][$costId]) && isset($costInput['renewal_amount'][$costId])
						? $costInput['renewal_amount'][$costId]
						: null,
					'stock' => 0,
					'weight' => 0.00,
					'length_amount' => $costInput['length_type'][$costId] == 'permanent' ? 0 : $costInput['length_amount'][$costId],
					'length_unit' => $costInput['length_type'][$costId] == 'permanent' ? '' : $costInput['length_unit'][$costId],
					'description' => $costInput['cost_description'][$costId],
					'highlighted' => $costInput['highlighted'] == $costId ? 1 : 0
				]);
			}
			else
			{
				$creator->addProductCost($costId, [
					'title' => $costInput['cost_title'][$costId],
					'cost_amount' => $costInput['cost_amount'][$costId],
					'stock' => $product->hasStockFunctionality() ? $costInput['stock'][$costId] : 0,
					'weight' => $product->hasWeight() ? $costInput['weight'][$costId] : 0.00,
					'length_amount' => 0,
					'length_unit' => '',
					'description' => $costInput['cost_description'][$costId],
					'highlighted' => $costInput['highlighted'] == $costId ? 1 : 0
				]);
			}
		}
		$productFields = $this->filter('product_fields', 'array');
		$creator->setProductFields($productFields);
		
		$shippingZones = $this->filter('shipping_zones', 'array-uint');
		$creator->setShippingZones($shippingZones);
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Product\Create $creator
	 */
	protected function finalizeProductCreate(\DBTech\eCommerce\Service\Product\Create $creator)
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $creator->getProduct();
		
		if (!$product->parent_product_id)
		{
			$creator->sendNotifications();
		}
		
		if (\XF::visitor()->user_id)
		{
			if ($product->product_state == 'moderated')
			{
				$this->session()->setHasContentPendingApproval();
			}
		}
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		$categoryId = $this->filter('category_id', 'uint');
		if ($categoryId)
		{
			$category = $this->assertViewableCategory($categoryId);
			if (!$category->canAddProduct($error))
			{
				return $this->noPermission($error);
			}
			
			$productType = $this->filter('product_type', 'str');
			if ($productType)
			{
				if ($productType == 'physical')
				{
					$shippingZoneRepo = $this->getShippingZoneRepo();
					if (!$shippingZoneRepo->findShippingZonesForList()->total())
					{
						throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_admin_create_shipping_zone_before_continuing')));
					}
				}
				
				/** @var \DBTech\eCommerce\Entity\Product $product */
				$product = $category->getNewProduct($productType);
			}
			else
			{
				$viewParams = [
					'category'   => $category,
				];
				return $this->view('DBTech\eCommerce:Product\AddChooser\Type', 'dbtech_ecommerce_product_add_chooser_type', $viewParams);
			}
		}
		else
		{
			$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/add'));
			
			$categoryRepo = $this->getCategoryRepo();
			
			$categories = $categoryRepo->getViewableCategories();
			$canAdd = false;
			
			foreach ($categories AS $category)
			{
				/** @var \DBTech\eCommerce\Entity\Category $category */
				if ($category->canAddProduct())
				{
					$canAdd = true;
					break;
				}
			}
			
			if (!$canAdd)
			{
				return $this->noPermission();
			}
			
			$categoryTree = $categoryRepo->createCategoryTree($categories);
			$categoryTree = $categoryTree->filter(null, function ($id, \DBTech\eCommerce\Entity\Category $category, $depth, $children): bool
			{
				if ($children)
				{
					return true;
				}
				if ($category->canAddProduct())
				{
					return true;
				}
				
				return false;
			});
			
			$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);
			
			$productType = $this->filter('product_type', 'str');
			if ($productType)
			{
				foreach ($categoryExtras as &$extra)
				{
					$extra['product_type'] = $productType;
				}
			}
			
			$viewParams = [
				'categoryTree' => $categoryTree,
				'categoryExtras' => $categoryExtras
			];
			
			return $this->view('DBTech\eCommerce:Product\AddChooser', 'dbtech_ecommerce_product_add_chooser', $viewParams);
		}
		
		return $this->productAddEdit($product);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\Product\Edit
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupProductEdit(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\Product\Edit
	{
		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$description = $editorPlugin->fromInput('description_full');
		$specification = $editorPlugin->fromInput('product_specification');
		$copyright = $editorPlugin->fromInput('copyright_info');
		
		/** @var \DBTech\eCommerce\Service\Product\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Product\Edit', $product);
		$editor->setPerformValidations(false);
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'is_featured' => 'bool',
			'is_discountable' => 'bool',
			'is_listed' => 'bool',
			'welcome_email' => 'bool',
			'is_all_access' => 'bool',
			'all_access_group_ids' => 'array-uint',
			'license_prefix' => 'str',
			'product_type_data' => 'array',
			'has_demo' => 'bool',
			'extra_group_ids' => 'array-uint',
			'temporary_extra_group_ids' => 'array-uint',
			'support_node_id' => 'uint',
			'thread_node_id' => 'uint',
			'thread_prefix_id' => 'uint',
			'branding_free' => 'uint',
			'global_branding_free' => 'uint',
		]);
		$editor->getProduct()->bulkSet($bulkInput);
		
		$editor->setTagLine($this->filter('tagline', 'str'));
		$editor->setDescription($this->filter('description', 'str'));
		
		$editor->setContent($description, $specification, $copyright);
		
		$productFields = $this->filter('product_fields', 'array');
		$editor->setProductFields($productFields);

		$welcomeEmail = $this->filter('welcome_email_options', 'array');
		$editor->setWelcomeEmail($welcomeEmail);
		
		$shippingZones = $this->filter('shipping_zones', 'array-uint');
		$editor->setShippingZones($shippingZones);
		
		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId != $product->prefix_id && !$product->Category->isPrefixUsable($prefixId))
		{
			$prefixId = 0; // not usable, just blank it out
		}
		$editor->setPrefix($prefixId);
		
		if ($product->canEditTags())
		{
			$editor->setTags($this->filter('tags', 'str'));
		}
		
		$editor->setRequirements($this->filter('requirements', 'str'));
		
		if (!$product->isAddOn())
		{
			$editor->setVersions(
				$this->filter('product_version', 'array-str'),
				$this->filter('product_version_text', 'array-str')
			);
		}
		
		if ($product->Category->canUploadAndManageProductImages())
		{
			$editor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		$filterIds = $this->filter('available_filters', 'array-str');
		$editor->setAvailableFilters($filterIds);
		
		$fieldIds = $this->filter('available_fields', 'array-str');
		$editor->setAvailableFields($fieldIds);
		
		$costInput = $this->filter([
			'cost_title' => 'array-str',
			'cost_amount' => 'array-float',
			'renewal_type' => 'array-str',
			'renewal_amount' => 'array-float',
			'length_type' => 'array-str',
			'length_amount' => 'array-str',
			'length_unit' => 'array-str',
			'stock' => 'array-uint',
			'weight' => 'array-float',
			'cost_description' => 'array-str',
			'highlighted' => 'uint'
		]);
		$costIds = array_keys($costInput['cost_amount']);
		
		foreach ($costIds as $costId)
		{
			if ($product->hasLicenseFunctionality())
			{
				$editor->addProductCost($costId, [
					'title' => $costInput['cost_title'][$costId] ?? '',
					'cost_amount' => $costInput['cost_amount'][$costId],
					'renewal_type' => $costInput['renewal_type'][$costId] ?? null,
					'renewal_amount' => isset($costInput['renewal_type'][$costId]) && isset($costInput['renewal_amount'][$costId])
						? $costInput['renewal_amount'][$costId]
						: null,
					'stock' => 0,
					'weight' => 0.00,
					'length_amount' => $costInput['length_type'][$costId] == 'permanent' ? 0 : $costInput['length_amount'][$costId],
					'length_unit' => $costInput['length_type'][$costId] == 'permanent' ? '' : $costInput['length_unit'][$costId],
					'description' => $costInput['cost_description'][$costId],
					'highlighted' => $costInput['highlighted'] == $costId ? 1 : 0
				]);
			}
			else
			{
				$editor->addProductCost($costId, [
					'title' => $costInput['cost_title'][$costId],
					'cost_amount' => $costInput['cost_amount'][$costId],
					'stock' => $product->hasStockFunctionality() ? $costInput['stock'][$costId] : 0,
					'weight' => $product->hasWeight() ? $costInput['weight'][$costId] : 0.00,
					'length_amount' => 0,
					'length_unit' => '',
					'description' => $costInput['cost_description'][$costId],
					'highlighted' => $costInput['highlighted'] == $costId ? 1 : 0
				]);
			}
		}
		if ($this->filter('author_alert', 'bool') && $product->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		return $editor;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionSave(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$this->assertPostOnly();
		
		if ($params->product_id)
		{
			$product = $this->assertViewableProduct($params->product_id);
			if (!$product->canEdit($error))
			{
				return $this->noPermission($error);
			}
			
			$editor = $this->setupProductEdit($product);
			$editor->checkForSpam();
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			
			$editor->save();
			$this->finalizeProductEdit($editor);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$categoryId = $this->filter('category_id', 'uint');
		$category = $this->assertViewableCategory($categoryId);
		if (!$category->canAddProduct($error))
		{
			return $this->noPermission($error);
		}
		
		$creator = $this->setupProductCreate($category);
		$creator->checkForSpam();
		
		if (!$creator->validate($errors))
		{
			throw $this->exception($this->error($errors));
		}
		$this->assertNotFlooding('post');
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $creator->save();
		$this->finalizeProductCreate($creator);
		
		if (!$product->canView())
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce/categories', $category, ['pending_approval' => 1]));
		}
		
		return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Product\Edit $editor
	 */
	protected function finalizeProductEdit(\DBTech\eCommerce\Service\Product\Edit $editor)
	{
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionAddOnAdd(ParameterBag $params)
	{
		/** @var \DBTech\eCommerce\Entity\Product $parentProduct */
		$parentProduct = $this->assertViewableProduct($params->product_id);
		if (!$parentProduct->canAddAddOn($error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $parentProduct->getNewAddOn();
		
		return $this->productAddEdit($product);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Entity\Product $targetProduct
	 *
	 * @return \DBTech\eCommerce\Service\Product\AddOnMove
	 * @throws \LogicException
	 */
	protected function setupProductAddOnMove(\DBTech\eCommerce\Entity\Product $product, \DBTech\eCommerce\Entity\Product $targetProduct): \DBTech\eCommerce\Service\Product\AddOnMove
	{
		$options = $this->filter([
			'notify_watchers' => 'bool',
			'author_alert' => 'bool',
			'author_alert_reason' => 'str',
			'prefix_id' => 'uint'
		]);
		
		/** @var \DBTech\eCommerce\Service\Product\AddOnMove $mover */
		$mover = $this->service('DBTech\eCommerce:Product\AddOnMove', $product);
		
		if ($options['author_alert'])
		{
			$mover->setSendAlert(true, $options['author_alert_reason']);
		}
		
		if ($options['notify_watchers'])
		{
			$mover->setNotifyWatchers();
		}
		
		$mover->addExtraSetup(function (\DBTech\eCommerce\Entity\Product $product, \DBTech\eCommerce\Entity\Product $targetProduct)
		{
			$product->title = $this->filter('title', 'str');
			$product->save();
		});
		
		return $mover;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionAddOnMove(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canChangeParent($error))
		{
			return $this->noPermission($error);
		}
		
		$productName = $this->filter('product', 'str');
		$targetProductId = $this->filter('target_product_id', 'uint');
		
		if ($productName)
		{
			/** @var \DBTech\eCommerce\Finder\Product $finder */
			$finder = $this->finder('DBTech\eCommerce:Product');
			$finder->searchText($productName, false, false, true);
			
			/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
			$targetProduct = $finder->fetchOne();
			if ($targetProduct->isValidAddOnParent())
			{
				$this->setupProductAddOnMove($product, $targetProduct)->move($targetProduct);
				
				return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
			}
		}
		elseif ($targetProductId)
		{
			/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
			$targetProduct = $this->em()->find('DBTech\eCommerce:Product', $targetProductId);
			if ($targetProduct->isValidAddOnParent())
			{
				$this->setupProductAddOnMove($product, $targetProduct)->move($targetProduct);
				
				return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
			}
		}
		
		$viewParams = [
			'product' => $product,
			'prefixes' => $product->Category->getUsablePrefixes()
		];
		return $this->view('DBTech\eCommerce:Product\AddOnMove', 'dbtech_ecommerce_product_addon_move', $viewParams);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionCostRow(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$this->assertPostOnly();
		
		$product = $params->product_id ? $this->assertViewableProduct($params->product_id) : null;
		if ($product && !$product->canEdit($error))
		{
			return $this->noPermission($error);
		}
		
		$json = [];
		
		$delete = $this->filter('delete', 'uint');
		if ($delete)
		{
			$productCost = $this->assertProductCostExists($delete);
			
			$productCost->delete();
			
			$json['delete'] = $delete;
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\ProductCost $productCost */
			$productCost = $this->em()->create('DBTech\eCommerce:ProductCost');
			$productCost->set('product_id', $params->product_id ?: 0, ['forceSet' => true]);
			$productCost->product_type = $this->filter('product_type', 'str');
			$productCost->title = '';
			$productCost->cost_amount = 5;
			$productCost->stock = 0;
			$productCost->weight = 0;
			$productCost->length_amount = 0;
			$productCost->length_unit = '';
			$productCost->save();
			
			$json['cost'] = $productCost->toArray();
		}
		
		$reply = $this->redirect($this->buildLink('dbtech-ecommerce/edit', $product));
		$reply->setJsonParams($json);
		
		return $reply;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionBookmark(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		/** @var \XF\ControllerPlugin\Bookmark $bookmarkPlugin */
		$bookmarkPlugin = $this->plugin('XF:Bookmark');
		
		return $bookmarkPlugin->actionBookmark(
			$product,
			$this->buildLink('dbtech-ecommerce/bookmark', $product)
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionDelete(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}
		
		$numDownloads = $this->finder('DBTech\eCommerce:Download')->where('product_id', $product->product_id)->total();
		$numLicenses = $this->finder('DBTech\eCommerce:License')->where('product_id', $product->product_id)->total();
		
		if ($this->isPost())
		{
			if ($product->product_state == 'deleted')
			{
				$type = $this->filter('hard_delete', 'uint');
				switch ($type)
				{
					case 0:
						return $this->redirect($this->buildLink('dbtech-ecommerce/categories', $product->Category));
					
					case 1:
						$reason = $this->filter('reason', 'str');
						if (!$product->canDelete('hard', $error))
						{
							return $this->noPermission($error);
						}
						
						/** @var \DBTech\eCommerce\Service\Product\Delete $deleter */
						$deleter = $this->service('DBTech\eCommerce:Product\Delete', $product);
						
						if ($this->filter('author_alert', 'bool'))
						{
							$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
						}
						
						$deleter->delete('hard', $reason);
						
						/** @var \XF\ControllerPlugin\InlineMod $inlineModPlugin */
						$inlineModPlugin = $this->plugin('XF:InlineMod');
						$inlineModPlugin->clearIdFromCookie('dbtech_ecommerce_product', $product->product_id);
						
						return $this->redirect($this->buildLink('dbtech-ecommerce/categories', $product->Category));
					
					case 2:
						if (!$product->canUndelete($error))
						{
							return $this->noPermission($error);
						}
						
						/** @var \DBTech\eCommerce\Service\Product\Delete $deleter */
						$deleter = $this->service('DBTech\eCommerce:Product\Delete', $product);
						
						if ($this->filter('author_alert', 'bool'))
						{
							$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
						}
						
						$deleter->unDelete();
						
						return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
				}
			}
			else
			{
				$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
				$reason = $this->filter('reason', 'str');
				if (!$product->canDelete($type, $error))
				{
					return $this->noPermission($error);
				}
				
				/** @var \DBTech\eCommerce\Service\Product\Delete $deleter */
				$deleter = $this->service('DBTech\eCommerce:Product\Delete', $product);
				
				if ($this->filter('author_alert', 'bool'))
				{
					$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
				}
				
				$deleter->delete($type, $reason);
				
				/** @var \XF\ControllerPlugin\InlineMod $inlineModPlugin */
				$inlineModPlugin = $this->plugin('XF:InlineMod');
				$inlineModPlugin->clearIdFromCookie('dbtech_ecommerce_product', $product->product_id);
				
				return $this->redirect($this->buildLink('dbtech-ecommerce'));
			}
		}

		$productRepo = $this->getProductRepo();
		$productTree = $productRepo->createProductTree(null, $product->product_id);

		$viewParams = [
			'product' => $product,
			'hasChildren' => $productTree->countChildren() > 0,
			'numDownloads' => $numDownloads,
			'numLicenses' => $numLicenses
		];
		return $this->view('DBTech\eCommerce:Product\Delete', 'dbtech_ecommerce_product_delete', $viewParams);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionUndelete(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);

		/** @var \XF\ControllerPlugin\Undelete $plugin */
		$plugin = $this->plugin('XF:Undelete');
		return $plugin->actionUndelete(
			$product,
			$this->buildLink('dbtech-ecommerce/undelete', $product),
			$this->buildLink('dbtech-ecommerce', $product),
			$product->title,
			'product_state'
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionReassign(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canReassign($error))
		{
			return $this->noPermission($error);
		}
		
		if ($this->isPost())
		{
			$userName = $this->filter('username', 'str');
			
			/** @var \XF\Entity\User $user */
			$user = $this->em()->findOne('XF:User', ['username' => $userName]);
			if (!$user)
			{
				return $this->error(\XF::phrase('requested_user_x_not_found', ['name' => $userName]));
			}
			
			$canTargetView = \XF::asVisitor($user, function () use ($product): bool
			{
				return $product->canView();
			});
			if (!$canTargetView)
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_new_owner_must_be_able_to_view_this_product'));
			}
			
			/** @var \DBTech\eCommerce\Service\Product\Reassign $reassigner */
			$reassigner = $this->service('DBTech\eCommerce:Product\Reassign', $product);
			
			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}
			
			$reassigner->reassignTo($user);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$viewParams = [
			'product' => $product,
			'category' => $product->Category
		];
		return $this->view('XF:Product\Reassign', 'dbtech_ecommerce_product_reassign', $viewParams);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionChangeThread(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canChangeDiscussionThread($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Product\ChangeDiscussion $changer */
			$changer = $this->service('DBTech\eCommerce:Product\ChangeDiscussion', $product);

			$threadAction = $this->filter('thread_action', 'str');
			if ($threadAction == 'disconnect')
			{
				$changer->disconnectDiscussion();
			}
			else
			{
				$threadUrl = $this->filter('thread_url', 'str');

				if (!$changer->changeThreadByUrl($threadUrl, true, $error))
				{
					return $this->error($error);
				}
			}

			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}

		$viewParams = [
			'product' => $product,
			'category' => $product->Category
		];
		return $this->view(
			'DBTech\eCommerce:Product\ChangeThread',
			'dbtech_ecommerce_product_change_thread',
			$viewParams
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionMove(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $product->Category;
		
		if ($this->isPost())
		{
			$targetCategoryId = $this->filter('target_category_id', 'uint');
			
			/** @var \DBTech\eCommerce\Entity\Category $targetCategory */
			$targetCategory = $this->app()->em()->find('DBTech\eCommerce:Category', $targetCategoryId);
			if (!$targetCategory)
			{
				return $this->error(\XF::phrase('requested_category_not_found'));
			}
			
			$this->setupProductMove($product, $targetCategory)->move($targetCategory);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
		}
		
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->getViewableCategories();
		
		$viewParams = [
			'product' => $product,
			'category' => $category,
			'prefixes' => $category->getUsablePrefixes(),
			'categoryTree' => $categoryRepo->createCategoryTree($categories)
		];
		return $this->view('DBTech\eCommerce:Product\Move', 'dbtech_ecommerce_product_move', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Entity\Category $category
	 *
	 * @return \DBTech\eCommerce\Service\Product\Move
	 */
	protected function setupProductMove(\DBTech\eCommerce\Entity\Product $product, \DBTech\eCommerce\Entity\Category $category): \DBTech\eCommerce\Service\Product\Move
	{
		$options = $this->filter([
			'notify_watchers' => 'bool',
			'author_alert' => 'bool',
			'author_alert_reason' => 'str',
			'prefix_id' => 'uint'
		]);
		
		/** @var \DBTech\eCommerce\Service\Product\Move $mover */
		$mover = $this->service('DBTech\eCommerce:Product\Move', $product);
		
		if ($options['author_alert'])
		{
			$mover->setSendAlert(true, $options['author_alert_reason']);
		}
		
		if ($options['notify_watchers'])
		{
			$mover->setNotifyWatchers();
		}
		
		if ($options['prefix_id'] !== null)
		{
			$mover->setPrefix($options['prefix_id']);
		}
		
		$mover->addExtraSetup(function (\DBTech\eCommerce\Entity\Product $product, \DBTech\eCommerce\Entity\Category $category)
		{
			$product->title = $this->filter('title', 'str');
		});
		
		return $mover;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionReport(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canReport($error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'dbtech_ecommerce_product',
			$product,
			$this->buildLink('dbtech-ecommerce/report', $product),
			$this->buildLink('dbtech-ecommerce', $product)
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionReact(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canReact($error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($product, 'dbtech-ecommerce');
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Message|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionReactions(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		$breadcrumbs = $product->Category->getBreadcrumbs();
		
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$product,
			'dbtech-ecommerce/reactions',
			null,
			$breadcrumbs
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionIp(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		$breadcrumbs = $product->getBreadcrumbs();
		
		/** @var \XF\ControllerPlugin\Ip $ipPlugin */
		$ipPlugin = $this->plugin('XF:Ip');
		return $ipPlugin->actionIp($product, $breadcrumbs);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionWarn(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		if (!$product->canWarn($error))
		{
			return $this->noPermission($error);
		}
		
		$breadcrumbs = $product->getBreadcrumbs();
		
		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'dbtech_ecommerce_product',
			$product,
			$this->buildLink('dbtech-ecommerce/warn', $product),
			$breadcrumbs
		);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionApprove(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
		
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \DBTech\eCommerce\Service\Product\Approve $approver */
		$approver = \XF::service('DBTech\eCommerce:Product\Approve', $product);
		$approver->approve();
		
		return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionUnapprove(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
		
		$product = $this->assertViewableProduct($params->product_id);
		if (!$product->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
		
		$product->product_state = 'moderated';
		$product->save();
		
		return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionAutoComplete(): \XF\Mvc\Reply\AbstractReply
	{
		$q = ltrim($this->filter('q', 'str', ['no-trim']));
		
		if ($q !== '' && \mb_strlen($q) >= 2)
		{
			/** @var \DBTech\eCommerce\Finder\Product $productFinder */
			$productFinder = $this->finder('DBTech\eCommerce:Product');
			$productFinder = $productFinder->searchText($q);
			
			$productType = $this->filter('product_type', 'str');
			if ($productType)
			{
				$productFinder->where('product_type', $productType);
			}
			
			$noChildren = $this->filter('no_children', 'bool');
			if ($noChildren)
			{
				$productFinder->where('parent_product_id', 0);
			}
			
			/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
			$visitor = \XF::visitor();
			
			$productFinder->with([
				'User',
				'Category',
				'Category.Permissions|' . $visitor->permission_combination_id
			]);
			
			$products = $productFinder->fetch(10);
			$products = $products->filterViewable();
			
			if ($this->filter('only_valid_parents', 'bool'))
			{
				$products = $products->filter(function ($product): bool
				{
					/** @var \DBTech\eCommerce\Entity\Product $product */
					return $product->isValidAddOnParent();
				});
			}
		}
		else
		{
			$products = [];
			$q = '';
		}
		
		$viewParams = [
			'q' => $q,
			'products' => $products
		];
		return $this->view('DBTech\eCommerce:Product\Find', '', $viewParams);
	}
	
	/**
	 * @return array
	 */
	protected function getProductViewExtraWith(): array
	{
		$extraWith = [
			'Sale'
		];
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$extraWith[] = 'Watch|' . $userId;
			$extraWith[] = 'LatestVersion.Downloads|' . $userId;
			$extraWith[] = 'Reactions|' . $userId;
		}
		
		return $extraWith;
	}
	
	/**
	 * @param int|null $productId
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\Product
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableProduct(?int $productId, array $extraWith = []): \DBTech\eCommerce\Entity\Product
	{
		$visitor = \XF::visitor();

		$extraWith[] = 'Permissions|' . $visitor->permission_combination_id;
		$extraWith[] = 'User';
		$extraWith[] = 'Category';
		$extraWith[] = 'Category.Permissions|' . $visitor->permission_combination_id;
		$extraWith[] = 'LatestVersion';
		$extraWith[] = 'Discussion';
		$extraWith[] = 'Discussion.Forum';
		$extraWith[] = 'Discussion.Forum.Node';
		$extraWith[] = 'Discussion.Forum.Node.Permissions|' . $visitor->permission_combination_id;

		if ($visitor->user_id)
		{
			$extraWith[] = 'Watch|' . $visitor->user_id;
			$extraWith[] = 'Bookmarks|' . $visitor->user_id;
		}
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->em()->find('DBTech\eCommerce:Product', $productId, $extraWith);
		if (!$product)
		{
			throw $this->exception($this->notFound(\XF::phrase('dbtech_ecommerce_requested_product_not_found')));
		}
		
		if (!$product->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		return $product;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\License|null
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertValidLicenseParameter(\DBTech\eCommerce\Entity\Product $product, array $extraWith = []): ?\DBTech\eCommerce\Entity\License
	{
		$license = null;
		if ($licenseKey = $this->filter('license_key', 'str'))
		{
			/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
			$licenseRepo = $this->repository('DBTech\eCommerce:License');
			
			/** @var \DBTech\eCommerce\Entity\License $license */
			$license = $licenseRepo->findLicenseByKey($licenseKey)->with($extraWith)->fetchOne();
			
			if (!$license)
			{
				throw $this->exception($this->notFound(\XF::phrase('dbtech_ecommerce_requested_license_not_found')));
			}
			
			if (!$license->isValid($error))
			{
				throw $this->exception($this->noPermission($error));
			}
			
			if ($license->product_id != $product->product_id)
			{
				throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_this_license_not_for_this_product')));
			}
		}
		
		return $license;
	}
	
	/**
	 * @param int|null $categoryId
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\Category
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableCategory(?int $categoryId, array $extraWith = []): \DBTech\eCommerce\Entity\Category
	{
		$visitor = \XF::visitor();
		
		$extraWith[] = 'Permissions|' . $visitor->permission_combination_id;
		
		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->em()->find('DBTech\eCommerce:Category', $categoryId, $extraWith);
		if (!$category)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_category_not_found')));
		}
		
		if (!$category->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		return $category;
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\ProductCost
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertProductCostExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\ProductCost
	{
		return $this->assertRecordExists('DBTech\eCommerce:ProductCost', $id, $with, $phraseKey);
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Category|\XF\Mvc\Entity\Repository
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('DBTech\eCommerce:Category');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\ShippingZone|\XF\Mvc\Entity\Repository
	 */
	protected function getShippingZoneRepo()
	{
		return $this->repository('DBTech\eCommerce:ShippingZone');
	}
	
	/**
	 * @param array $activities
	 *
	 * @return array
	 */
	public static function getActivityDetails(array $activities): array
	{
		return self::getActivityDetailsForContent(
			$activities,
			\XF::phrase('dbtech_ecommerce_viewing_product'),
			'product_id',
			function (array $ids): array
			{
				$products = \XF::em()->findByIds(
					'DBTech\eCommerce:Product',
					$ids,
					['Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id]
				);
				
				$router = \XF::app()->router('public');
				$data = [];
				
				foreach ($products->filterViewable() AS $id => $product)
				{
					$data[$id] = [
						'title' => $product->title,
						'url' => $router->buildLink('dbtech-ecommerce', $product)
					];
				}
				
				return $data;
			},
			\XF::phrase('dbtech_ecommerce_viewing_products')
		);
	}
}