<?php

namespace Truonglv\Groups\Service\ResourceItem;

use XF;
use Throwable;
use XF\Util\File;
use LogicException;
use RuntimeException;
use XF\Service\AbstractService;
use XF\Service\ValidateAndSavableTrait;
use Truonglv\Groups\Entity\ResourceItem;

class Icon extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var ResourceItem
     */
    protected $resource;
    /**
     * @var \XF\Http\Upload|null
     */
    protected $upload;
    /**
     * @var array
     */
    protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];
    /**
     * @var string|null
     */
    protected $iconUrl;

    public function __construct(\XF\App $app, ResourceItem $resource)
    {
        parent::__construct($app);

        $this->resource = $resource;
    }

    public function deleteIcons(): void
    {
        $resource = $this->resource;
        \XF\Util\File::deleteFromAbstractedPath($resource->getAbstractedIconPath());

        $resource->icon_date = 0;
        $resource->icon_url = '';
        $resource->save();
    }

    /**
     * @param \XF\Http\Upload $upload
     */
    public function setUploadFile(\XF\Http\Upload $upload): void
    {
        $this->upload = $upload;
    }

    /**
     * @param string $iconUrl
     */
    public function setIconUrl(string $iconUrl): void
    {
        $this->iconUrl = $iconUrl;
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $errors = [];

        if ($this->upload !== null) {
            $file = $this->upload;
            $file->requireImage();
            if (!$this->upload->isValid($errors)) {
                return $errors;
            }

            $width = $file->getImageWidth();
            $height = $file->getImageHeight();

            if (!$this->app->imageManager()->canResize($width, $height)) {
                $errors[] = XF::phrase('uploaded_image_is_too_big');
            }

            if (!in_array($file->getImageType(), $this->allowedTypes, true)) {
                $errors[] = XF::phrase('provided_file_is_not_valid_image');
            }
        } elseif ($this->iconUrl !== null) {
            $tempFile = File::getTempFile();
            $client = $this->app->http()->client();

            try {
                $client->get($this->iconUrl, [
                    'sink' => $tempFile,
                    'timeout' => 3,
                ]);
            } catch (Throwable $e) {
            }

            $contentType = '';
            if (function_exists('mime_content_type')) {
                $contentType = mime_content_type($tempFile);
                $allowedTypes = [
                    'image/png',
                    'image/jpg',
                    'image/jpeg',
                    'image/gif',
                ];
            } else {
                $imageSize = getimagesize($tempFile);
                if (is_array($imageSize)) {
                    $contentType = $imageSize[2] ?? '';
                }

                $allowedTypes = [
                    IMAGETYPE_PNG,
                    IMAGETYPE_JPEG,
                    IMAGETYPE_GIF
                ];
            }

            if (!in_array($contentType, $allowedTypes, true)) {
                $errors[] = XF::phrase('image_is_invalid_type');
            }
        } else {
            throw new LogicException('Must be set upload file OR icon url');
        }

        return $errors;
    }

    /**
     * @return ResourceItem
     */
    protected function _save()
    {
        $resource = $this->resource;
        if ($this->iconUrl !== null) {
            $resource->fastUpdate([
                'icon_date' => 0,
                'icon_url' => $this->iconUrl,
            ]);

            return $resource;
        }

        $file = $this->upload;
        if ($file === null) {
            throw new LogicException('Must be set file');
        }

        $baseFile = $file->getTempFile();
        $width = $file->getImageWidth();
        $height = $file->getImageHeight();

        $imageManager = $this->app->imageManager();
        $maxSize = $this->app->options()->tl_groups_resourceIconSize;

        $shortSide = min($width, $height);

        $image = $imageManager->imageFromFile($baseFile);
        if ($image === null) {
            throw new RuntimeException('Failed to save image to temporary file; check internal_data/data permissions');
        }

        $image->resizeShortEdge($maxSize, $shortSide < $maxSize);
        $image->crop(
            $maxSize,
            $maxSize,
            max(0, ($image->getWidth() - $maxSize) / 2),
            max(0, ($image->getHeight() - $maxSize) / 2)
        );

        $newTempFile = \XF\Util\File::getTempFile();
        if ($newTempFile && $image->save($newTempFile)) {
            // save ok
        } else {
            throw new RuntimeException('Cannot save image');
        }
        unset($image);

        $output = $resource->getAbstractedIconPath();
        File::copyFileToAbstractedPath($newTempFile, $output);

        $resource->fastUpdate([
            'icon_url' => '',
            'icon_date' => time(),
        ]);

        return $resource;
    }
}
