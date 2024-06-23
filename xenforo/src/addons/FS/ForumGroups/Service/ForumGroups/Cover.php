<?php

namespace FS\ForumGroups\Service\ForumGroups;

use XF;
use function md5;
use XF\Util\File;
use function count;
use LogicException;
use XF\FileWrapper;
use function uniqid;
use function array_replace;
use InvalidArgumentException;
use XF\Repository\Attachment;

class Cover extends AbstractFormUpload
{
    const BASE_WIDTH = 918;
    const BASE_HEIGHT = 200;

    /**
     * @var array
     */
    protected $cropData = [];
    /**
     * @var bool
     */
    protected $adminLog = true;
    /**
     * @var array
     */
    protected $allowImageTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
    /**
     * @var bool
     */
    protected $skipCheckDimensions = false;

    /**
     * @return array
     */
    public static function getDefaultCropData()
    {
        return [
            'w' => self::BASE_WIDTH,
            'h' => self::BASE_HEIGHT,
            'x' => 0,
            'y' => 0,
            'imgW' => 0,
            'imgH' => 0
        ];
    }

    /**
     * @param bool $skipCheckDimensions
     * @return $this
     */
    public function setSkipCheckDimensions(bool $skipCheckDimensions)
    {
        $this->skipCheckDimensions = $skipCheckDimensions;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->app->router('public')->buildLink('forumGroups/cover', $this->subCommunity);
    }

    /**
     * @return \XF\Phrase
     */
    public function getFormFieldLabel()
    {
        return XF::phrase('tlg_upload_new_cover');
    }

    /**
     * @return bool
     */
    public function canDeleteExisting()
    {
        return $this->subCommunity->CoverAttachment !== null;
    }

    /**
     * @return array
     */
    public function getBaseDimensions()
    {
        return [self::BASE_WIDTH, self::BASE_HEIGHT];
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $errors = [];
        list($baseWidth, $baseHeight) = $this->getBaseDimensions();
        if (!$this->skipCheckDimensions && ($this->width < $baseWidth || $this->height < $baseHeight)) {
            $errors[] = XF::phrase('fs_group_please_upload_image_at_least_xy_pixels', [
                'width' => $baseWidth,
                'height' => $baseHeight
            ]);
        }

        return $errors;
    }

    /**
     * @param array $cropData
     * @return $this
     */
    public function setCropData(array $cropData)
    {
        $this->cropData = array_replace(static::getDefaultCropData(), $cropData);

        return $this;
    }

    /**
     * @param bool $adminLog
     * @return void
     */
    public function setAdminLog(bool $adminLog)
    {
        $this->adminLog = $adminLog;
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    public function saveCropData()
    {
        if (count($this->cropData) === 0 || $this->subCommunity->CoverAttachment === null) {
            throw new LogicException('Community did not have custom cover.');
        }

        $subCommunity = $this->subCommunity;
        $subCommunity->cover_crop_data = $this->cropData;
        $subCommunity->save();
    }

    /**
     * @return mixed
     * @throws \XF\PrintableException
     */
    protected function doUpload()
    {
        $uploadedFile = $this->upload;
        if ($uploadedFile === null) {
            throw new InvalidArgumentException('Must be set file');
        }

        $imageManager = $this->app->imageManager();
        $baseFile = $uploadedFile->getTempFile();

        $image = $imageManager->imageFromFile($baseFile);
        if ($image === null) {
            throw new InvalidArgumentException('Could not create image processor.');
        }

        list($baseWidth,) = $this->getBaseDimensions();
        $ratio = $baseWidth / $this->width;
        $image->resizeTo(ceil($ratio * $this->width), ceil($ratio * $this->height));

        $newTempFile = File::getTempFile();
        if ($newTempFile && $image->save($newTempFile, $this->type, $this->getCoverQuality())) {
            $baseFile = $newTempFile;
        } else {
            throw new InvalidArgumentException('Could not save cover image. Must be check permissions on internal_data folder.');
        }

        $subCommunity = $this->subCommunity;
        if ($subCommunity->CoverAttachment !== null) {
            // must delete existing cover
            $subCommunity->CoverAttachment->delete();
        }

        $fileWrapper = new FileWrapper($baseFile, $uploadedFile->getFileName());
        /** @var \XF\Service\Attachment\Preparer $attachmentPreparer */
        $attachmentPreparer = $this->service('XF:Attachment\Preparer');
        /** @var Attachment $attachmentRepo */
        $attachmentRepo = $this->repository('XF:Attachment');
        $handler = $attachmentRepo->getAttachmentHandler('node');

        if ($handler === null) {
            throw new InvalidArgumentException('Invalid handler');
        }

        $tempHash = md5(uniqid('', true));
        $attachment = $attachmentPreparer->insertAttachment($handler, $fileWrapper, XF::visitor(), $tempHash);

        $subCommunity->cover_attachment_id = $attachment->attachment_id;

        if (count($this->cropData) === 0) {
            $this->cropData = static::getDefaultCropData();
        }

        $cropData = $this->cropData;
        $cropData['imgW'] = $image->getWidth();
        $cropData['imgH'] = $image->getHeight();

        $subCommunity->cover_crop_data = $cropData;

        $subCommunity->save();

        $attachmentPreparer->associateAttachmentsWithContent(
            $tempHash,
            'node',
            $subCommunity->node_id
        );

        unset($image);

        return $subCommunity;
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    public function delete()
    {
        $subCommunity = $this->subCommunity;
        if ($subCommunity->CoverAttachment === null) {
            throw new LogicException('Community(' . $subCommunity->node_id . ') did not have an custom cover');
        }

        $subCommunity->CoverAttachment->delete();

        $subCommunity->cover_attachment_id = 0;
        $subCommunity->cover_crop_data = static::getDefaultCropData();

        $subCommunity->save();
    }

    /**
     * @return int
     */
    protected function getCoverQuality()
    {
        return 96;
    }
}
