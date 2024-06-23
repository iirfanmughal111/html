<?php

namespace DBTech\eCommerce\Download;

use XF\Service\AbstractService;
use DBTech\eCommerce\Entity\Download;
use DBTech\eCommerce\Entity\License;

/**
 * Class AbstractHandler
 *
 * @package DBTech\eCommerce\Download
 */
abstract class AbstractHandler
{
	/** @var string */
	protected $contentType;


	/**
	 * AbstractHandler constructor.
	 *
	 * @param string $contentType
	 */
	public function __construct(string $contentType)
	{
		$this->contentType = $contentType;
	}
	
	/**
	 * @param Download $download
	 *
	 * @return array
	 */
	abstract public function getEditData(Download $download): array;
	
	/**
	 * @param Download $download
	 *
	 * @return array
	 */
	abstract public function getDownloadData(Download $download): array;
	
	/**
	 * @param AbstractService $service
	 * @param array $data
	 */
	abstract public function setEditData(AbstractService $service, array $data = []);
	
	
	/**
	 * @return bool
	 */
	public function isEnabled(): bool
	{
		return true;
	}
	
	/**
	 * @return string|null
	 */
	public function getEditTemplateName(): ?string
	{
		return 'public:' . $this->contentType . '_download_edit';
	}
	
	/**
	 * @return string|null
	 */
	public function getDownloadTemplateName(): ?string
	{
		return 'public:' . $this->contentType . '_download_download';
	}
	
	/**
	 * @param Download $download
	 *
	 * @return array
	 */
	public function getEditTemplateData(Download $download): array
	{
		return [
			'download' => $download,
			'data' => $download->EditData
		];
	}
	
	/**
	 * @param Download $download
	 * @param License|null $license
	 *
	 * @return array
	 */
	public function getDownloadTemplateData(Download $download, ?License $license = null): array
	{
		return [
			'download' => $download,
			'data' => $download->DownloadData,
			'license' => $license
		];
	}
	
	/**
	 * @param Download $download
	 *
	 * @return string
	 */
	public function renderEdit(Download $download): string
	{
		$template = $this->getEditTemplateName();
		if (!$template)
		{
			return '';
		}
		return \XF::app()->templater()->renderTemplate($template, $this->getEditTemplateData($download));
	}
	
	/**
	 * @param Download $download
	 * @param License|null $license
	 *
	 * @return string
	 */
	public function renderDownload(Download $download, ?License $license = null): string
	{
		$template = $this->getDownloadTemplateName();
		if (!$template)
		{
			return '';
		}
		return \XF::app()->templater()->renderTemplate($template, $this->getDownloadTemplateData($download, $license));
	}
	
	/**
	 * @return \XF\Db\AbstractAdapter
	 */
	public function db(): \XF\Db\AbstractAdapter
	{
		return \XF::app()->db();
	}
	
	/**
	 * @param string $identifier
	 *
	 * @return \XF\Mvc\Entity\Repository
	 */
	public function repository(string $identifier): \XF\Mvc\Entity\Repository
	{
		return \XF::app()->em()->getRepository($identifier);
	}
}