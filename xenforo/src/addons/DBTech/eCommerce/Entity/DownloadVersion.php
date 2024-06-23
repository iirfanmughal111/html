<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $download_version_id
 * @property int $download_id
 * @property int $product_id
 * @property string $product_version
 * @property string $product_version_type
 * @property string $directories
 * @property int $attach_count
 * @property string $download_url
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Download $Download
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 */
class DownloadVersion extends Entity
{
	/**
	 * @param License|null $license
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canDownload(?License $license = null, &$error = null): bool
	{
		$download = $this->Download;
		
		if (!$download || !$download->canDownload($license, $error))
		{
			return false;
		}
		
		switch ($download->download_type)
		{
			case 'dbtech_ecommerce_attach':
				if (!$this->Attachments || !$this->Attachments->count())
				{
					$error = \XF::phraseDeferred('dbtech_ecommerce_download_version_has_no_attachment');
					return false;
				}
				break;
				
			case 'dbtech_ecommerce_autogen':
				if ($this->directories)
				{
					foreach ($this->getDirectoryList() as $dir)
					{
						if (
							!is_dir($dir)
							|| !is_readable($dir)
						) {
							$error = \XF::phraseDeferred('dbtech_ecommerce_download_dir_not_readable', ['directory' => $dir]);
							return false;
						}
					}
				}
				else
				{
					$error = \XF::phraseDeferred('dbtech_ecommerce_download_version_has_no_directories');
					return false;
				}
				break;
				
			case 'dbtech_ecommerce_external':
				if ($this->download_url)
				{
					$censoredUrl = $this->app()->stringFormatter()->censorText($this->download_url);
					if ($censoredUrl !== $this->download_url)
					{
						$error = \XF::phraseDeferred('dbtech_ecommerce_download_is_not_available_try_another');
						return false;
					}
				}
				else
				{
					$error = \XF::phraseDeferred('dbtech_ecommerce_download_version_has_no_url');
					return false;
				}
				break;
		}
		
		return true;
	}
	
	/**
	 * @return array
	 */
	public function getDirectoryList(): array
	{
		if ($this->Download->download_type != 'dbtech_ecommerce_autogen' || empty($this->directories))
		{
			return [];
		}
		
		return preg_split('#\r?\n#', $this->directories, -1, PREG_SPLIT_NO_EMPTY);
	}
	
	/**
	 * @param License|null $license
	 *
	 * @return string
	 */
	public function getReleaseFileName(?License $license = null): string
	{
		$options = $this->app()->options();
		$template = $options->dbtechEcommerceGeneratedFilename;
		
		if (!$template)
		{
			$template = '{title}';
		}
		
		$tokens = [];
		$tokens['{title}'] = $this->Download->title;
		$tokens['{license_key}'] = ($license ? $license->license_key : \XF::phrase('dbtech_ecommerce_demo'));
		
		return strtr($template, $tokens) . '.zip';
	}
	
	/**
	 * @param License|null $license
	 *
	 * @return string
	 */
	public function getReleaseAbstractPath(?License $license = null): string
	{
		$user = $license ? $license->User : \XF::visitor();
		
		return sprintf(
			'internal-data://dbtechEcommerce/releases/%d/%s/%d/%d-%s',
			$this->Download->download_id,
			$this->product_version . '_' . $this->product_version_type,
			$user->user_id,
			$license ? $license->license_id : 0,
			$this->getReleaseFileName($license)
		);
	}

	/**
	 * @return string
	 */
	public function getBuildJsonPath(): string
	{
		$directories = $this->getDirectoryList();
		if (!$directories)
		{
			return '';
		}
		
		$ds = DIRECTORY_SEPARATOR;
		foreach ($directories as $directory)
		{
			$directory = rtrim($directory, $ds);
			if (file_exists($directory . $ds . 'build.json'))
			{
				return $directory . $ds . 'build.json';
			}
		}
		
		return '';
	}
	
	/**
	 * @return array
	 */
	public function getBuildJson(): array
	{
		$buildJsonPath = $this->getBuildJsonPath();
		$buildJson = [
			'additional_files' => [],
			'minify' => [],
			'rollup' => [],
			'exec' => [],
			'hashes' => [],
			'replaceTokens' => true
		];
		if (file_exists($buildJsonPath))
		{
			$parsedBuildJson = @json_decode(file_get_contents($buildJsonPath), true);
			
			$buildJson = array_replace(
				$buildJson,
				$parsedBuildJson ?: []
			);
		}
		
		return $buildJson;
	}
	
	/**
	 * @param int $downloadId
	 * @return bool
	 */
	protected function verifyDownload(int &$downloadId): bool
	{
		if ($this->isInsert())
		{
			// Allow empty download ID on insert
			return true;
		}

		if (!$downloadId)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_download_id'), 'download_id');
			return false;
		}
		
		/** @var \DBTech\eCommerce\Entity\Download $download */
		$download = $this->_em->find('DBTech\eCommerce:Download', $downloadId);
		if (!$download)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_download_id'), 'download_id');
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	protected function _preSave(): bool
	{
		if ($this->isUpdate() && !$this->download_id)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_download_id'), 'download_id');
			return false;
		}
		
		if ($this->Download)
		{
			$this->product_id = $this->Download->product_id;
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Download $download */
			$download = $this->_em->find('DBTech\eCommerce:Download', $this->download_id);
			
			$this->product_id = $download->product_id;
		}
		
		return true;
	}
	
	/**
	 *
	 */
	protected function _postDelete()
	{
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('dbtech_ecommerce_version', $this->download_version_id);
	}
	
	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-type DownloadVersion
	 *
	 * @api-out bool $can_download
	 */
	protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result,
		$verbosity = self::VERBOSITY_NORMAL,
		array $options = []
	) {
		if (empty($options['licenses']))
		{
			if ($verbosity > self::VERBOSITY_NORMAL && \XF::visitor()->user_id)
			{
				/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
				$licenseRepo = $this->repository('DBTech\eCommerce:License');
				
				$licenseFinder = $licenseRepo->findLicensesByUser(
					\XF::visitor()->user_id,
					null,
					['allowOwnPending' => false]
				);
				$licenseFinder->where('product_id', $this->product_id);
				
				/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
				$licenses = $licenseFinder->fetch();
				$licenses = $licenseRepo->filterLicensesForApiResponse($licenses);
			}
			else
			{
				$licenses = new ArrayCollection([]);
			}
		}
		else
		{
			$licenses = $options['licenses'];
		}
		
		if (!empty($options['with_product']))
		{
			$result->includeRelation('Product', $verbosity, [
				'licenses' => $licenses
			]);
		}
		
		$result->can_download = false;
		
		if ($licenses->count())
		{
			/** @var \DBTech\eCommerce\Entity\License $license */
			foreach ($licenses as $license)
			{
				if ($this->canDownload($license))
				{
					$result->can_download = true;
					break;
				}
			}
		}
		elseif ($this->product_version_type == 'demo')
		{
			$result->can_download = $this->canDownload();
		}
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_download_version';
		$structure->shortName = 'DBTech\eCommerce:DownloadVersion';
		$structure->primaryKey = 'download_version_id';
		$structure->columns = [
			'download_version_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'download_id' => ['type' => self::UINT, 'required' => true, 'default' => 0,
				'verify' => 'verifyDownload', 'api' => true
			],
			'product_id' => ['type' => self::UINT, 'api' => true],
			'product_version' => ['type' => self::STR, 'required' => true, 'api' => true],
			'product_version_type' => ['type' => self::STR, 'default' => 'full',
				'allowedValues' => ['full', 'demo'], 'api' => true
			],
			'directories' => ['type' => self::STR, 'default' => '', 'api' => true],
			'attach_count' => ['type' => self::UINT, 'default' => 0],
			'download_url' => ['type' => self::STR, 'default' => '',
				'censor' => true,
				'match' => 'url_empty',
				'api' => true
			],
		];
		$structure->behaviors = [];
		$structure->getters = [];
		$structure->relations = [
			'Download' => [
				'entity' => 'DBTech\eCommerce:Download',
				'type' => self::TO_ONE,
				'conditions' => 'download_id',
				'primary' => true
			],
			'Attachments' => [
				'entity' => 'XF:Attachment',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_version'],
					['content_id', '=', '$download_version_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],
		];
		$structure->options = [];

		return $structure;
	}
}