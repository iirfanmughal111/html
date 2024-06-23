<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\ParameterBag;

/**
 * @api-group Downloads
 */
class Downloads extends AbstractLoggableEndpoint
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		parent::preDispatchController($action, $params);
		
		$this->assertApiScopeByRequestMethod('dbtech_ecommerce_download');
	}
	
	/**
	 * @api-desc Creates a new download
	 *
	 * @api-see self::setupDownloadCreate()
	 * @api-in <req> str $product_id
	 * @api-in <req> str $download_type One of (currently) three download types; "dbtech_ecommerce_autogen", "dbtech_ecommerce_attachment" or "dbtech_ecommerce_external"
	 * @api-in <req> str $version_string
	 * @api-in <req> array $handler_data An array of information needed by the specified download handler. This should be inferred by the templates.
	 *
	 * @api-out Download $download Information about the created download
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionPost(): \XF\Mvc\Reply\AbstractReply
	{
		$this->assertRequiredApiInput(['product_id', 'download_type', 'version_string', 'handler_data']);

		$productId = $this->filter('product_id', 'uint');

		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->assertViewableApiRecord('DBTech\eCommerce:Product', $productId);

		if (\XF::isApiCheckingPermissions() && !$product->canReleaseUpdate($error))
		{
			return $this->noPermission($error);
		}

		$creator = $this->setupDownloadCreate($product);

		if (!$creator->validate($errors))
		{
			return $this->error($errors);
		}

		/** @var \DBTech\eCommerce\Entity\Download $download */
		$download = $creator->save();
		
		$this->finalizeDownloadCreate($creator);

		return $this->apiSuccess([
			'download' => $download->toApiResult()
		]);
	}
	
	/**
	 * @api-in str $change_log The change log entry for this download.
	 * @api-in str $release_notes The release notes for this download. If this is omitted, a release thread / post will not be generated.
	 * @api-in array $handler_data An array of information needed by the specified download handler. This should be inferred by the templates.
	 * @api-in str $change_log_attachment_key API attachment key to upload files. Attachment key context type must be dbtech_download_download with context[download_id] set to this download ID.
	 * @api-in str $release_notes_attachment_key API attachment key to upload files. Attachment key context type must be dbtech_download_download with context[download_id] set to this download ID.
	 * @api-in str $version_string
	 * @api-in bool $has_new_features
	 * @api-in bool $has_changed_features
	 * @api-in bool $has_bug_fixes
	 * @api-in bool $is_unstable
	 *
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\Download\Create
	 * @throws \Exception
	 */
	protected function setupDownloadCreate(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\Download\Create
	{
		$handler = $this->getDownloadRepo()->getDownloadHandler($this->filter('download_type', 'str'), true);
		
		/** @var \DBTech\eCommerce\Service\Download\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Download\Create', $product, $handler);
		
		$input = $this->filter([
			'change_log' => '?str',
			'release_notes' => '?str',
			
			'handler_data' => 'array',
			
			'change_log_attachment_key' => 'str',
			'release_notes_attachment_key' => 'str',
		]);
		
		$basicFields = $this->filter([
			'version_string' => 'str',
			'has_new_features' => '?bool',
			'has_changed_features' => '?bool',
			'has_bug_fixes' => '?bool',
			'is_unstable' => '?bool',
			'release_date' => '?bool',
		]);
		$basicFields = \XF\Util\Arr::filterNull($basicFields);
		
		if (!isset($basicFields['release_date']))
		{
			$basicFields['release_date'] = \XF::$time;
		}
		
		$creator->getDownload()->bulkSet($basicFields);
		
		if (isset($input['change_log']))
		{
			$creator->setChangeLog($input['change_log']);
		}
		if (isset($input['release_notes']))
		{
			$creator->setReleaseNotes($input['release_notes']);
		}
		
		$handler->setEditData($creator, $this->filter('handler_data', 'array'));

		return $creator;
	}
	
	protected function finalizeDownloadCreate(\DBTech\eCommerce\Service\Download\Create $creator)
	{
		$creator->sendNotifications();
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Download|\XF\Mvc\Entity\Repository
	 */
	protected function getDownloadRepo()
	{
		return $this->repository('DBTech\eCommerce:Download');
	}
}