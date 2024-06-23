<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Download
 * @package DBTech\eCommerce\Repository
 */
class Download extends Repository
{

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Finder\DownloadVersion
	 * @throws \InvalidArgumentException
	 */
	public function findDownloadVersionsInProduct(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Finder\DownloadVersion
	{
		/** @var \DBTech\eCommerce\Finder\DownloadVersion $finder */
		$finder = $this->finder('DBTech\eCommerce:DownloadVersion');

		return $finder->inProduct($product)
					  ->hasDownloads();
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param array $limits
	 *
	 * @return \DBTech\eCommerce\Finder\Download
	 * @throws \InvalidArgumentException
	 */
	public function findDownloadsInProduct(\DBTech\eCommerce\Entity\Product $product, array $limits = []): \DBTech\eCommerce\Finder\Download
	{
		/** @var \DBTech\eCommerce\Finder\Download $finder */
		$finder = $this->finder('DBTech\eCommerce:Download');
		$finder->inProduct($product, $limits)
			->with('Product')
			->setDefaultOrder('release_date', 'desc');

		return $finder;
	}

	/**
	 * @param \XF\Entity\Thread $thread
	 *
	 * @return \DBTech\eCommerce\Finder\Download
	 */
	public function findDownloadForThread(\XF\Entity\Thread $thread): \DBTech\eCommerce\Finder\Download
	{
		/** @var \DBTech\eCommerce\Finder\Download $finder */
		$finder = $this->finder('DBTech\eCommerce:Download');

		$finder->where('discussion_thread_id', $thread->thread_id)
			->with('full')
			->with('Product.Category.Permissions|' . \XF::visitor()->permission_combination_id);

		return $finder;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Download $download
	 * @param string $action
	 * @param string $reason
	 * @param array $extra
	 *
	 * @return bool
	 */
	public function sendModeratorActionAlert(
		\DBTech\eCommerce\Entity\Download $download,
		string $action,
		string $reason = '',
		array $extra = []
	): bool {
		$product = $download->Product;
		
		if (!$product || !$product->user_id || !$product->User)
		{
			return false;
		}
		
		$extra = array_merge([
			'title' => $product->title,
			'prefix_id' => $product->prefix_id,
			'update' => $download->title,
			'link' => $this->app()->router('public')->buildLink('nopath:dbtech-ecommerce/release', $download),
			'productLink' => $this->app()->router('public')->buildLink('nopath:dbtech-ecommerce', $product),
			'reason' => $reason,
			'depends_on_addon_id' => 'DBTech/eCommerce',
		], $extra);
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$product->User,
			0,
			'',
			'user',
			$product->user_id,
			"dbt_ecom_download_{$action}",
			$extra
		);
		
		return true;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\DownloadVersion $version
	 * @param \DBTech\eCommerce\Entity\License|null $license
	 *
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function logDownload(
		\DBTech\eCommerce\Entity\DownloadVersion $version,
		?\DBTech\eCommerce\Entity\License $license = null
	) {
		$visitor = \XF::visitor();
		$download = $version->Download;
		
		if (!$visitor->user_id)
		{
			$updateProduct = true;
			$updateDownload = true;
		}
		else
		{
			$hasDownloaded = $this->db()->fetchOne('
				SELECT 1
				FROM xf_dbtech_ecommerce_product_download
				WHERE user_id = ?
					AND product_id = ?
				LIMIT 1
			', [$visitor->user_id, $download->product_id]);
			
			$updateProduct = !$hasDownloaded;
			
			$result = $this->db()->insert('xf_dbtech_ecommerce_product_download', [
				'download_id' => $download->download_id,
				'user_id' => $visitor->user_id,
				'product_id' => $download->product_id,
				'last_download_date' => \XF::$time
			], false, 'last_download_date = VALUES(last_download_date)');
			
			$updateDownload = ($result == 1);
		}
		
		if ($updateProduct)
		{
			$this->db()->query('
				UPDATE xf_dbtech_ecommerce_product
				SET download_count = download_count + 1
				WHERE product_id = ?
			', $download->product_id);
		}
		
		$this->db()->query('
			UPDATE xf_dbtech_ecommerce_product
			SET full_download_count = full_download_count + 1
			WHERE product_id = ?
		', $download->product_id);
		
		if ($updateDownload)
		{
			$this->db()->query('
				UPDATE xf_dbtech_ecommerce_download
				SET download_count = download_count + 1
				WHERE download_id = ?
			', $download->download_id);
		}
		
		$this->db()->query('
			UPDATE xf_dbtech_ecommerce_download
			SET full_download_count = full_download_count + 1
			WHERE download_id = ?
		', $download->download_id);
		
		if ($license)
		{
			$license->latest_download_id = $download->download_id;
			$license->saveIfChanged();
		}
		
		/** @var \DBTech\eCommerce\Entity\DownloadLog $downloadLog */
		$downloadLog = $this->em->create('DBTech\eCommerce:DownloadLog');
		$downloadLog->product_id = $download->product_id;
		$downloadLog->download_id = $download->download_id;
		$downloadLog->product_version = $version->product_version;
		$downloadLog->user_id = $visitor->user_id;
		
		if ($license)
		{
			$downloadLog->license_id = $license->license_id;
			
			/** @var \XF\CustomField\Set $fieldSet */
			$fieldSet = $downloadLog->license_fields;
			$fieldDefinition = $fieldSet->getDefinitionSet()
				->filterEditable($fieldSet, 'user');
			
			$licenseFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
			
			if ($licenseFieldsShown)
			{
				$fieldSet->bulkSet($license->license_fields_, $licenseFieldsShown, 'user', true);
			}
		}
		
		$downloadLog->save();
		
		/** @var \XF\Repository\Ip $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		$ipEnt = $ipRepo->logIp(
			$visitor->user_id,
			$this->app()->request()->getIp(),
			'dbtech_ecommerce_download',
			$download->download_id,
			'download'
		);
		if ($ipEnt)
		{
			$downloadLog->fastUpdate('ip_id', $ipEnt->ip_id);
		}
	}

	/**
	 * @return Finder
	 */
	public function findDownloadsForList(): Finder
	{
		/** @var \DBTech\eCommerce\Finder\Download $finder */
		$finder = $this->finder('DBTech\eCommerce:Download');

		return $finder->orderForList();
	}
	
	
	/**
	 * @return \DBTech\eCommerce\Download\AbstractHandler[]
	 * @throws \Exception
	 */
	public function getDownloadHandlers(): array
	{
		$handlers = [];
		
		foreach (\XF::app()->getContentTypeField('dbtech_ecommerce_download_handler_class') AS $contentType => $identifier)
		{
			$handlerClass = \XF::stringToClass($identifier, '%s\Download\%s');
			
			if (class_exists($handlerClass))
			{
				$handlerClass = \XF::extendClass($handlerClass);
				$handlers[$contentType] = new $handlerClass($contentType);
			}
		}
		
		return $handlers;
	}
	
	/**
	 * @param string $type
	 * @param bool $throw
	 *
	 * @return \DBTech\eCommerce\Download\AbstractHandler|null
	 * @throws \Exception
	 */
	public function getDownloadHandler(string $type, bool $throw = false): ?\DBTech\eCommerce\Download\AbstractHandler
	{
		$identifier = \XF::app()->getContentTypeFieldValue($type, 'dbtech_ecommerce_download_handler_class');
		$handlerClass = \XF::stringToClass($identifier, '%s\Download\%s');
		
		if (!$handlerClass)
		{
			if ($throw)
			{
				throw new \InvalidArgumentException("No download handler for '$type'");
			}
			return null;
		}
		
		if (!class_exists($handlerClass))
		{
			if ($throw)
			{
				throw new \InvalidArgumentException("Download handler for '$type' does not exist: $handlerClass");
			}
			return null;
		}
		
		$handlerClass = \XF::extendClass($handlerClass);
		return new $handlerClass($type);
	}
}