<?php

namespace DBTech\eCommerce\Service\Order;

/**
 * Class InvoiceIcon
 * @package DBTech\eCommerce\Service\Order
 */
class InvoiceIcon extends \XF\Service\AbstractService
{
	/** @var string */
	protected $fileName;
	
	/** @var string */
	protected $extension;

	/** @var null */
	protected $error;

	/** @var array */
	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];


	/**
	 * @return null
	 */
	public function getError()
	{
		return $this->error;
	}
	
	/**
	 * @param string $fileName
	 * @param string $extension
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function setImage(string $fileName, string $extension): bool
	{
		if (!$this->validateImageAsIcon($fileName, $error))
		{
			$this->error = $error;
			$this->fileName = null;
			return false;
		}
		
		$this->fileName = $fileName;
		$this->extension = $extension;
		return true;
	}
	
	/**
	 * @param \XF\Http\Upload $upload
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function setImageFromUpload(\XF\Http\Upload $upload): bool
	{
		$upload->requireImage();

		if (!$upload->isValid($errors))
		{
			$this->error = reset($errors);
			return false;
		}

		return $this->setImage($upload->getTempFile(), $upload->getExtension());
	}
	
	/**
	 * @param string $fileName
	 * @param null $error
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function validateImageAsIcon(string $fileName, &$error = null): bool
	{
		$error = null;

		if (!file_exists($fileName))
		{
			throw new \InvalidArgumentException("Invalid file '$fileName' passed to icon service");
		}
		if (!is_readable($fileName))
		{
			throw new \InvalidArgumentException("'$fileName' passed to icon service is not readable");
		}

		$imageInfo = filesize($fileName) ? getimagesize($fileName) : false;
		if (!$imageInfo)
		{
			$error = \XF::phrase('provided_file_is_not_valid_image');
			return false;
		}

		$type = $imageInfo[2];
		if (!in_array($type, $this->allowedTypes))
		{
			$error = \XF::phrase('provided_file_is_not_valid_image');
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 * @throws \RuntimeException
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function updateIcon(): bool
	{
		if (!$this->fileName)
		{
			throw new \LogicException('No source file for icon set');
		}

		$outputFile = $this->fileName;

		if (!$outputFile)
		{
			throw new \RuntimeException('Failed to save image to temporary file; check internal_data/data permissions');
		}
		
		$newFileName = 'logo.' . $this->extension;

		$dataFile = $this->getAbstractedIconPath($newFileName);
		\XF\Util\File::copyFileToAbstractedPath($outputFile, $dataFile);
		
		$this->getOptionRepo()->updateOptions([
			'dbtechEcommerceInvoiceIconPath' => $newFileName,
			'dbtechEcommerceInvoiceIconDate' => \XF::$time
		]);

		return true;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function deleteIcon(): bool
	{
		$this->deleteIconFiles();
		
		$this->getOptionRepo()->updateOptions([
			'dbtechEcommerceInvoiceIconPath' => '',
			'dbtechEcommerceInvoiceIconDate' => 0
		]);

		return true;
	}
	
	/**
	 *
	 */
	protected function deleteIconFiles()
	{
		if (\XF::options()->dbtechEcommerceInvoiceIconPath)
		{
			\XF\Util\File::deleteFromAbstractedPath($this->getAbstractedIconPath());
		}
	}
	
	/**
	 * @param string|null $fileName
	 *
	 * @return string
	 */
	public function getAbstractedIconPath(string $fileName = null): string
	{
		return sprintf(
			'data://dbtechEcommerce/invoiceIcons/%s',
			$fileName ?: \XF::options()->dbtechEcommerceInvoiceIconPath
		);
	}
	
	/**
	 * @return \XF\Repository\Option|\XF\Mvc\Entity\Repository
	 */
	protected function getOptionRepo()
	{
		return $this->repository('XF:Option');
	}
}