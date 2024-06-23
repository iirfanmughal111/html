<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;

/**
 * @api-group Downloads
 */
class Download extends AbstractLoggableEndpoint
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

		$this->assertApiScopeByRequestMethod('dbtech_ecommerce_download');
	}
	
	/**
	 * @api-desc Gets information about the specified download
	 *
	 * @api-out Download $download
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGet(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$download = $this->assertViewableDownload($params->download_id);

		$result = [
			'download' => $download->toApiResult(Entity::VERBOSITY_VERBOSE, [
				'with_product' => true,
				'with_versions' => true,
			])
		];
		return $this->apiResult($result);
	}

	/**
	 * @api-desc Gets the data that makes up the specified download. The output is the raw binary data.
	 *
	 * @api-in <req> str $product_version Which product version you want to download
	 * @api-in <req> str $product_version_type Which product version type you want to download ("demo" or "full")
	 *
	 * @api-out binary $data The binary data is output directly, not JSON.
	 *
	 * @api-error you_have_not_purchased_any_licenses_yet Triggered if requesting the full download, and no licenses match the search parameters.
	 * @api-error license_does_not_permit_download Triggered if requesting the full download, and the license does not permit the download (f.ex. expired).
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionGetDownload(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);

		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $download->Product;

		if (\XF::isApiCheckingPermissions() && !$product->canDownload($error))
		{
			return $this->error($error);
		}

		$this->assertRequiredApiInput('product_version');
		$this->assertRequiredApiInput('product_version_type');
		
		$versionFinder = $download->getRelationFinder('Versions');
		
		$productVersion = $this->filter('product_version', 'str');
		$versionFinder->where('product_version', $productVersion);
		
		$productVersionType = $this->filter('product_version_type', 'str');
		$versionFinder->where('product_version_type', $productVersionType);
		
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $downloadVersion */
		$downloadVersion = $versionFinder->fetchOne();
		if (!$downloadVersion)
		{
			return $this->notFound();
		}

		$license = null;
		if ($productVersionType == 'demo')
		{
			if (!$downloadVersion->canDownload(null, $error))
			{
				return $this->error($error);
			}
		}
		else
		{
			/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
			$licenseRepo = $this->repository('DBTech\eCommerce:License');
			
			$licenseFinder = $licenseRepo->findLicensesByUser(
				\XF::visitor()->user_id,
				null,
				['allowOwnPending' => false]
			);
			$licenseFinder->where('product_id', $download->product_id);
			
			/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
			$licenses = $licenseFinder->fetch();
			$licenses = $licenseRepo->filterLicensesForApiResponse($licenses);
			
			if (!$licenses->count())
			{
				return $this->apiError(
					\XF::phrase('api_error.dbtech_ecommerce_you_have_not_purchased_any_licenses_yet'),
					'you_have_not_purchased_any_licenses_yet',
					[],
					402
				);
			}
			
			$canDownload = false;
			foreach ($licenses as $license)
			{
				if ($downloadVersion->canDownload($license))
				{
					$canDownload = true;
					break;
				}
			}
			
			if (\XF::isApiCheckingPermissions() && !$canDownload)
			{
				return $this->apiError(
					\XF::phrase('api_error.dbtech_ecommerce_license_does_not_permit_download'),
					'license_does_not_permit_download',
					[],
					402
				);
			}
		}

		/** @var \DBTech\eCommerce\ControllerPlugin\AbstractDownload $downloadPlugin */
		$downloadPlugin = $this->plugin($downloadVersion->Download->getHandlerIdentifier());
		return $downloadPlugin->download($downloadVersion, $license);
	}
	
	/**
	 * @api-desc Updates the specified download
	 *
	 * @api-see self::setupDownloadEdit()
	 *
	 * @api-out Download $download The updated download information
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionPost(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);
		
		if (\XF::isApiCheckingPermissions() && !$download->canEdit($error))
		{
			return $this->noPermission($error);
		}
		
		$editor = $this->setupDownloadEdit($download);
		
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
			'download' => $download->toApiResult()
		]);
	}
	
	/**
	 * @api-in str $change_log The change log entry for this download.
	 * @api-in str $release_notes The release notes for this download. If this is omitted, a release thread / post will not be generated.
	 * @api-in array $handler_data An array of information needed by the specified download handler. This should be inferred by the templates.
	 * @api-in bool $author_alert
	 * @api-in str $author_alert_reason
	 * @api-in str $change_log_attachment_key API attachment key to upload files. Attachment key context type must be dbtech_ecommerce_download with context[download_id] set to this download ID.
	 * @api-in str $release_notes_attachment_key API attachment key to upload files. Attachment key context type must be dbtech_ecommerce_download with context[download_id] set to this download ID.
	 * @api-in str $version_string
	 * @api-in bool $has_new_features
	 * @api-in bool $has_changed_features
	 * @api-in bool $has_bug_fixes
	 * @api-in bool $is_unstable
	 *
	 * @param \DBTech\eCommerce\Entity\Download $download
	 *
	 * @return \DBTech\eCommerce\Service\Download\Edit
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function setupDownloadEdit(\DBTech\eCommerce\Entity\Download $download): \DBTech\eCommerce\Service\Download\Edit
	{
		$input = $this->filter([
			'change_log' => '?str',
			'release_notes' => '?str',
			
			'handler_data' => 'array',
			
			'author_alert' => 'bool',
			'author_alert_reason' => 'str',
			
			'change_log_attachment_key' => 'str',
			'release_notes_attachment_key' => 'str',
		]);
		
		/** @var \DBTech\eCommerce\Service\Download\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Download\Edit', $download);
		
		$basicFields = $this->filter([
			'version_string' => '?str',
			'has_new_features' => '?bool',
			'has_changed_features' => '?bool',
			'has_bug_fixes' => '?bool',
			'is_unstable' => '?bool',
		]);
		$basicFields = \XF\Util\Arr::filterNull($basicFields);
		$download->bulkSet($basicFields);
		
		if (isset($input['change_log']))
		{
			$editor->setChangeLog($input['change_log']);
		}
		if (isset($input['release_notes']))
		{
			$editor->setReleaseNotes($input['release_notes']);
		}
		
		if (\XF::isApiBypassingPermissions() || $download->Product->Category->canUploadAndManageProductImages())
		{
			$hash = $this->getAttachmentTempHashFromKey(
				$input['change_log_attachment_key'],
				'dbtech_ecommerce_download',
				['download_id' => $download->download_id]
			);
			$editor->setChangeLogAttachmentHash($hash);
			
			$hash = $this->getAttachmentTempHashFromKey(
				$input['release_notes_attachment_key'],
				'dbtech_ecommerce_download',
				['download_id' => $download->download_id]
			);
			$editor->setChangeLogAttachmentHash($hash);
		}
		
		if ($input['handler_data'])
		{
			$editor->getHandler()
				->setEditData($editor, $input['handler_data'])
			;
		}
		
		if ($this->filter('author_alert', 'bool') && $download->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		return $editor;
	}

	/**
	 * @api-desc Deletes the specified download. Defaults to soft deletion.
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
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionDelete(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);

		if (\XF::isApiCheckingPermissions() && !$download->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}
		
		$type = 'soft';
		$reason = $this->filter('reason', 'str');
		
		if ($this->filter('hard_delete', 'bool'))
		{
			$this->assertApiScope('dbtech_ecommerce_download:delete_hard');
			
			if (\XF::isApiCheckingPermissions() && !$download->canDelete('hard', $error))
			{
				return $this->noPermission($error);
			}
			
			$type = 'hard';
		}
		
		/** @var \DBTech\eCommerce\Service\Download\Delete $deleter */
		$deleter = $this->service('DBTech\eCommerce:Download\Delete', $download);
		
		if ($this->filter('author_alert', 'bool') && $download->canSendModeratorActionAlert())
		{
			$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		$deleter->delete($type, $reason);

		return $this->apiSuccess();
	}

	/**
	 * @param int|null $id
	 * @param string|array $with
	 *
	 * @return \DBTech\eCommerce\Entity\Download|\XF\Mvc\Entity\Entity
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 * @noinspection PhpReturnDocTypeMismatchInspection
	 */
	protected function assertViewableDownload(?int $id, $with = 'api')
	{
		return $this->assertViewableApiRecord('DBTech\eCommerce:Download', $id, $with);
	}
}