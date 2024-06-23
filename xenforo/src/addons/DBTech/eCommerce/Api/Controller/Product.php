<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;

/**
 * @api-group Products
 */
class Product extends AbstractLoggableEndpoint
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\PrintableException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		parent::preDispatchController($action, $params);
		
		$this->assertApiScopeByRequestMethod('dbtech_ecommerce_product');
	}

	/**
	 * @api-desc Gets information about the specified product
	 *
	 * @api-out Product $product
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGet(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$product = $this->assertViewableProduct($params->product_id);

		$result = [
			'product' => $product->toApiResult()
		];
		return $this->apiResult($result);
	}

	/**
	 * @api-desc Gets downloads belonging to the specified product.
	 *
	 * @api-in int $page
	 * @api-in str $product_version Which product version you want to get downloads for.
	 * @api-in str $product_version_type Which product version type you want to get downloads for ("demo" or "full").
	 *
	 * @api-out Download[] $downloads Downloads on this page
	 * @api-out pagination $pagination Pagination information
	 *
	 * @api-error no_downloadable_version_found Triggered if no download version exist matching the search parameters.
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\Error
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGetDownloads(ParameterBag $params)
	{
		$this->assertApiScope('dbtech_ecommerce_download:read');
		$product = $this->assertViewableProduct($params->product_id);

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceDownloadsPerPage;

		$finder = $this->setupDownloadFinder($product);

		/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
		$downloadRepo = $this->repository('DBTech\eCommerce:Download');
		$versionFinder = $downloadRepo->findDownloadVersionsInProduct($product);
		
		$productVersion = $this->filter('product_version', 'str');
		if ($productVersion)
		{
			$versionFinder->where('product_version', $productVersion);
		}
		
		$productVersionType = $this->filter('product_version_type', 'str');
		if ($productVersionType)
		{
			$versionFinder->where('product_version_type', $productVersionType);
		}
		
		$downloadVersions = $versionFinder->fetch();
		if (!$downloadVersions->count())
		{
			return $this->apiError(
				\XF::phrase('api_error.dbtech_ecommerce_no_downloadable_version_found'),
				'no_downloadable_version_found',
				[],
				404
			);
		}
		$finder->where('download_id', $downloadVersions->pluckNamed('download_id', 'download_id'));
		
		$total = $finder->total();

		$this->assertValidApiPage($page, $perPage, $total);

		$downloads = $finder->limitByPage($page, $perPage)->fetch();
		
		$options = [
			'licenses' => new ArrayCollection([])
		];
		if (\XF::visitor()->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
			$licenseRepo = $this->repository('DBTech\eCommerce:License');
			
			$licenseFinder = $licenseRepo->findLicensesByUser(
				\XF::visitor()->user_id,
				null,
				['allowOwnPending' => false]
			);
			$licenseFinder->where('product_id', $product->product_id);
			
			/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
			$licenses = $licenseFinder->fetch();
			$licenses = $licenseRepo->filterLicensesForApiResponse($licenses);
			
			if ($licenses->count())
			{
				$options['licenses'] = $licenses;
			}
		}
		
		$downloadResults = $downloads->toApiResults(Entity::VERBOSITY_VERBOSE, $options);
		
		$result = [
			'downloads' => $downloadResults,
			'pagination' => $this->getPaginationData($downloadResults, $page, $perPage, $total)
		];
		return $this->apiResult($result);
	}
	
	/**
	 * @api-desc Gets the latest download matching the search terms.
	 *
	 * @api-in str $product_version Which product version you want to get the latest version for.
	 * @api-in str $product_version_type Which product version type you want to get the latest version for ("demo" or "full").
	 *
	 * @api-out Download $latestVersion Information about the specified download
	 *
	 * @api-error no_downloadable_version_found Triggered if no download version exist matching the search parameters.
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\Error
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGetLatestVersion(ParameterBag $params)
	{
		$this->assertApiScope('dbtech_ecommerce_download:read');
		$product = $this->assertViewableProduct($params->product_id, ['api', 'LatestVersion']);
		
		$downloadVersions = null;
		
		$productVersion = $this->filter('product_version', 'str');
		$productVersionType = $this->filter('product_version_type', 'str');
		if ($productVersion || $productVersionType)
		{
			$finder = $this->setupDownloadFinder($product);

			/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
			$downloadRepo = $this->repository('DBTech\eCommerce:Download');
			$versionFinder = $downloadRepo->findDownloadVersionsInProduct($product);
			
			if ($productVersion)
			{
				$versionFinder->where('product_version', $productVersion);
			}
			
			if ($productVersionType)
			{
				$versionFinder->where('product_version_type', $productVersionType);
			}
			
			$downloadVersions = $versionFinder->fetch();
			if (!$downloadVersions->count())
			{
				return $this->apiError(
					\XF::phrase('api_error.dbtech_ecommerce_no_downloadable_version_found'),
					'no_downloadable_version_found',
					[],
					404
				);
			}
			$finder->where('download_id', $downloadVersions->pluckNamed('download_id', 'download_id'));
			
			$latestVersion = $finder->fetchOne();
		}
		else
		{
			// No filtering needed
			$latestVersion = $product->LatestVersion;
		}
		
		$result = [
			'latestVersion' => $latestVersion->toApiResult(Entity::VERBOSITY_VERBOSE, ['with_product' => true]),
		];
		return $this->apiResult($result);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Finder\Download
	 */
	protected function setupDownloadFinder(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Finder\Download
	{
		/** @var \DBTech\eCommerce\Finder\Download $finder */
		$finder = $this->finder('DBTech\eCommerce:Download');
		$finder
			->inProduct($product)
			->setDefaultOrder('release_date', 'desc')
			->with('api');

		return $finder;
	}

	/**
	 * @api-desc Gets reviews belonging to the specified product.
	 *
	 * @api-in int $page
	 *
	 * @api-out ProductRating[] $reviews Reviews on this page
	 * @api-out pagination $pagination Pagination information
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGetReviews(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$this->assertApiScope('dbtech_ecommerce_rating:read');

		$product = $this->assertViewableProduct($params->product_id);

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceReviewsPerPage;

		$finder = $this->setupReviewFinder($product);
		$total = $finder->total();

		$this->assertValidApiPage($page, $perPage, $total);

		$reviews = $finder->limitByPage($page, $perPage)->fetch();
		$reviewResults = $reviews->toApiResults(Entity::VERBOSITY_VERBOSE);

		return $this->apiResult([
			'reviews' => $reviewResults,
			'pagination' => $this->getPaginationData($reviewResults, $page, $perPage, $total)
		]);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Finder\ProductRating
	 */
	protected function setupReviewFinder(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Finder\ProductRating
	{
		/** @var \DBTech\eCommerce\Finder\ProductRating $finder */
		$finder = $this->finder('DBTech\eCommerce:ProductRating');
		$finder
			->inProduct($product)
			->where('is_review', 1)
			->setDefaultOrder('rating_date', 'desc')
			->with('api');

		return $finder;
	}
	
	/**
	 * @api-in str $title
	 * @api-in bool $is_discountable Whether this product can be discounted by automatic discounts.
	 * @api-in array $product_type_data An array of product type data.
	 * @api-in str $license_prefix A string that'll be used for the prefix for generated license keys.
	 * @api-in bool $has_demo Whether this product has a "demo" download.
	 * @api-in int $thread_node_id
	 * @api-in int $thread_prefix_id
	 * @api-in int $branding_free The product ID for the Branding Free product.
	 * @api-in int $global_branding_free The product ID for the Global Branding Free product.
	 * @api-in int $prefix_id
	 * @api-in str $tagline
	 * @api-in str $description A short description that'll be displayed next to the product images.
	 * @api-in str $description_full The full description to be displayed in the "Overview" tab.
	 * @api-in str $product_specification The list of specifications for the product to be displayed in the "Specifications" tab.
	 * @api-in str $copyright_info The list of copyright info for the product to be displayed in the "Copyright" tab.
	 * @api-in str $requirements Comma-separated list of requirements.
	 * @api-in array $product_fields
	 * @api-in array $add_tags
	 * @api-in array $remove_tags
	 * @api-in array $available_filters
	 * @api-in array $available_fields
	 * @api-in array $shipping_zones
	 * @api-in array $extra_group_ids User group changes that will persist even after a license expires.
	 * @api-in array $temporary_extra_group_ids User group changes that will expire when a license expires.
	 * @api-in bool $author_alert
	 * @api-in str $author_alert_reason
	 * @api-in str $attachment_key API attachment key to upload screenshots. Attachment key context type must be dbtech_ecommerce_product with context[product_id] set to this product ID.
	 *
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\Product\Edit
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function setupProductEdit(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\Product\Edit
	{
		$input = $this->filter([
			'prefix_id' => '?uint',
			'tagline' => '?str',
			'description' => '?str',
			'description_full' => '?str',
			'product_specification' => '?str',
			'copyright_info' => '?str',
			'requirements' => '?str',
			
			'product_fields' => 'array',
			
			'add_tags' => 'array-str',
			'remove_tags' => 'array-str',
			
			'available_filters' => 'array-str',
			'available_fields' => 'array-str',
			
			'shipping_zones' => 'array-uint',
			'extra_group_ids' => 'array-uint',
			'temporary_extra_group_ids' => 'array-uint',
			
			'author_alert' => 'bool',
			'author_alert_reason' => 'str',
			
			'attachment_key' => 'str'
		]);
		
		/** @var \DBTech\eCommerce\Service\Product\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Product\Edit', $product);
		
		$basicFields = $this->filter([
			'title' => '?str',
			'is_featured' => '?bool',
			'is_discountable' => '?bool',
			'is_listed' => '?bool',
			'is_all_access' => '?bool',
			'all_access_group_ids' => '?array-uint',
			'product_type_data' => '?array',
			'license_prefix' => '?str',
			'has_demo' => '?bool',
			'support_node_id' => '?uint',
			'thread_node_id' => '?uint',
			'thread_prefix_id' => '?uint',
			'branding_free' => '?uint',
			'global_branding_free' => '?uint',
		]);
		$basicFields = \XF\Util\Arr::filterNull($basicFields);
		$product->bulkSet($basicFields);
		
		$isBypassingPermissions = \XF::isApiBypassingPermissions();
		$isCheckingPermissions = \XF::isApiCheckingPermissions();
		
		if (isset($input['prefix_id']))
		{
			$prefixId = $input['prefix_id'];
			if ($prefixId != $product->prefix_id
				&& $isCheckingPermissions
				&& !$product->Category->isPrefixUsable($input['prefix_id'])
			) {
				$prefixId = 0; // not usable, just blank it out
			}
			$editor->setPrefix($prefixId);
		}
		
		if (isset($input['tagline']))
		{
			$editor->setTagLine($input['tagline']);
		}
		
		if (isset($input['description']))
		{
			$editor->setDescription($input['description']);
		}
		
		if (isset($input['description_full']))
		{
			$editor->getDescriptionPreparer()->setMessage($input['description_full']);
		}
		
		if (isset($input['product_specification']))
		{
			$editor->getSpecificationPreparer()->setMessage($input['product_specification']);
		}
		
		if (isset($input['copyright_info']))
		{
			$editor->getCopyrightPreparer()->setMessage($input['copyright_info']);
		}
		
		if (isset($input['requirements']))
		{
			$editor->setRequirements($input['requirements']);
		}
		
		if ($input['product_fields'])
		{
			$editor->setProductFields($input['product_fields'], true);
		}
		
		if ($input['shipping_zones'])
		{
			$editor->setShippingZones($input['shipping_zones']);
		}
		
		if ($input['available_filters'])
		{
			$editor->setAvailableFilters($input['available_filters']);
		}
		
		if ($input['available_fields'])
		{
			$editor->setAvailableFields($input['available_fields']);
		}
		
		if ($isBypassingPermissions || $product->canEditTags())
		{
			if ($input['add_tags'])
			{
				$editor->addTags($input['add_tags']);
			}
			if ($input['remove_tags'])
			{
				$editor->removeTags($input['remove_tags']);
			}
		}
		
		if ($isBypassingPermissions || $product->Category->canUploadAndManageProductImages())
		{
			$hash = $this->getAttachmentTempHashFromKey(
				$input['attachment_key'],
				'dbtech_ecommerce_product',
				['product_id' => $product->product_id]
			);
			$editor->setAttachmentHash($hash);
		}
		
		if ($input['author_alert'] && $product->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $input['author_alert_reason']);
		}

		return $editor;
	}
	
	/**
	 * @api-desc Updates the specified product
	 *
	 * @api-see self::setupProductEdit()
	 *
	 * @api-out Product $product The updated product information
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionPost(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);
		
		if (\XF::isApiCheckingPermissions() && !$product->canEdit($error))
		{
			return $this->noPermission($error);
		}
		
		$editor = $this->setupProductEdit($product);
		
		if (\XF::isApiCheckingPermissions())
		{
			$editor->checkForSpam();
		}
		
		if (!$editor->validate($errors))
		{
			return $this->error($errors);
		}
		
		$editor->save();
		
		return $this->apiSuccess([
			'product' => $product->toApiResult()
		]);
	}
	
	/**
	 * @api-desc Deletes the specified product. Defaults to soft deletion.
	 *
	 * @api-in bool $hard_delete
	 * @api-in str $reason
	 * @api-in bool $author_alert
	 * @api-in str $author_alert_reason
	 *
	 * @api-out bool $success
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionDelete(ParameterBag $params)
	{
		$product = $this->assertViewableProduct($params->product_id);

		if (\XF::isApiCheckingPermissions() && !$product->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		$type = 'soft';
		$reason = $this->filter('reason', 'str');

		if ($this->filter('hard_delete', 'bool'))
		{
			$this->assertApiScope('dbtech_ecommerce_product:delete_hard');

			if (\XF::isApiCheckingPermissions() && !$product->canDelete('hard', $error))
			{
				return $this->noPermission($error);
			}

			$type = 'hard';
		}

		/** @var \DBTech\eCommerce\Service\Product\Delete $deleter */
		$deleter = $this->service('DBTech\eCommerce:Product\Delete', $product);

		if ($this->filter('author_alert', 'bool'))
		{
			$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}

		$deleter->delete($type, $reason);

		return $this->apiSuccess();
	}

	/**
	 * @param int $id
	 * @param string|array $with
	 *
	 * @return \DBTech\eCommerce\Entity\Product|Entity
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableProduct($id, $with = 'api')
	{
		return $this->assertViewableApiRecord('DBTech\eCommerce:Product', $id, $with);
	}
}