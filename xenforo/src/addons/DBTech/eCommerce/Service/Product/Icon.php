<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class Icon
 * @package DBTech\eCommerce\Service\Product
 */
class Icon extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;

	/** @var bool */
	protected $logIp = true;

	/** @var string */
	protected $fileName;

	/** @var string */
	protected $extension;

	/** @var int */
	protected $width;

	/** @var int */
	protected $height;

	/** @var null */
	protected $error;

	/** @var array */
	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];


	/**
	 * Icon constructor.
	 *
	 * @param \XF\App $app
	 * @param Product $product
	 */
	public function __construct(\XF\App $app, Product $product)
	{
		parent::__construct($app);
		$this->product = $product;
	}

	/**
	 * @return Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @param bool $logIp
	 */
	public function logIp(bool $logIp)
	{
		$this->logIp = $logIp;
	}

	/**
	 * @return null
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @param string $fileName
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function setImage(string $fileName): bool
	{
		if (!$this->validateImageAsIcon($fileName, $error))
		{
			$this->error = $error;
			$this->fileName = null;
			return false;
		}

		$this->fileName = $fileName;
		return true;
	}

	/**
	 * @param string $fileName
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function setSvgImage(string $fileName): bool
	{
		$this->width = $this->app->options()->dbtechEcommerceProductIconMaxDimensions['width'] ?:
			$this->app->container('avatarSizeMap')['l'];

		$this->height = $this->app->options()->dbtechEcommerceProductIconMaxDimensions['height'] ?:
			$this->app->container('avatarSizeMap')['l'];

		$this->fileName = $fileName;
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
		$this->extension = strtolower($upload->getExtension());
		if ($this->extension === 'svg')
		{
			return $this->setSvgImage($upload->getTempFile());
		}
		else
		{
			$upload->requireImage();

			if (!$upload->isValid($errors))
			{
				$this->error = reset($errors);
				return false;
			}

			return $this->setImage($upload->getTempFile());
		}
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

		[$width, $height] = $imageInfo;

		if (!$this->app->imageManager()->canResize($width, $height))
		{
			$error = \XF::phrase('uploaded_image_is_too_big');
			return false;
		}

		$this->width = $width;
		$this->height = $height;

		return true;
	}

	/**
	 * @return bool
	 * @throws \RuntimeException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function updateIcon(): bool
	{
		if (!$this->fileName)
		{
			throw new \LogicException('No source file for icon set');
		}

		$imageManager = $this->app->imageManager();

		$targetWidth = $this->app->options()->dbtechEcommerceProductIconMaxDimensions['width'] ?:
			$this->app->container('avatarSizeMap')['l'];

		$targetHeight = $this->app->options()->dbtechEcommerceProductIconMaxDimensions['height'] ?:
			$this->app->container('avatarSizeMap')['l'];

		$outputFile = null;

		if ($this->width != $targetWidth || $this->height != $targetHeight)
		{
			$image = $imageManager->imageFromFile($this->fileName);
			if (!$image)
			{
				return false;
			}

			$image->resizeAndCrop($targetWidth, $targetHeight);

			$newTempFile = \XF\Util\File::getTempFile();
			if ($newTempFile && $image->save($newTempFile))
			{
				$outputFile = $newTempFile;
			}
		}
		else
		{
			$outputFile = $this->fileName;
		}

		if (!$outputFile)
		{
			throw new \RuntimeException('Failed to save image to temporary file; check internal_data/data permissions');
		}

		$dataFile = $this->product->getAbstractedIconPath(null, $this->extension);
		\XF\Util\File::copyFileToAbstractedPath($outputFile, $dataFile);

		$this->product->icon_date = \XF::$time;
		$this->product->icon_extension = $this->extension;
		$this->product->save();

		if ($this->logIp)
		{
			$ip = ($this->logIp === true ? $this->app->request()->getIp() : $this->logIp);
			$this->writeIpLog('update', $ip);
		}

		return true;
	}

	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function deleteIcon(): bool
	{
		$this->deleteIconFiles();

		$this->product->icon_date = 0;
		$this->product->save();

		if ($this->logIp)
		{
			$ip = ($this->logIp === true ? $this->app->request()->getIp() : $this->logIp);
			$this->writeIpLog('delete', $ip);
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function deleteIconForProductDelete(): bool
	{
		$this->deleteIconFiles();

		return true;
	}

	/**
	 *
	 */
	protected function deleteIconFiles()
	{
		if ($this->product->icon_date)
		{
			\XF\Util\File::deleteFromAbstractedPath($this->product->getAbstractedIconPath());
		}
	}

	/**
	 * @param string $action
	 * @param string $ip
	 */
	protected function writeIpLog(string $action, string $ip)
	{
		$product = $this->product;

		/** @var \XF\Repository\Ip $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		$ipRepo->logIp(
			\XF::visitor()->user_id,
			$ip,
			'dbtech_ecommerce_product',
			$product->product_id,
			'icon_' . $action
		);
	}
}