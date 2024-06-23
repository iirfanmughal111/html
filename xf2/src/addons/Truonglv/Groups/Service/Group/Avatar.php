<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Group;

use XF;
use function max;
use function md5;
use function min;
use LogicException;
use XF\FileWrapper;
use function uniqid;
use RuntimeException;
use Truonglv\Groups\App;
use InvalidArgumentException;
use XF\Repository\Attachment;

class Avatar extends AbstractFormUpload
{
    /**
     * @var int
     */
    public static $size = 250;

    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $height;
    /**
     * @var int
     */
    protected $type;
    /**
     * @var null|string
     */
    protected $error = null;
    /**
     * @var bool
     */
    protected $validated = false;
    /**
     * @var bool
     */
    protected $adminLog = true;

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->app->router('public')->buildLink('groups/avatar', $this->group);
    }

    /**
     * @return \XF\Phrase
     */
    public function getFormFieldLabel()
    {
        return XF::phrase('tlg_upload_new_group_avatar');
    }

    /**
     * @return bool
     */
    public function canDeleteExisting()
    {
        return $this->group->AvatarAttachment !== null;
    }

    /**
     * @return array
     */
    public function getBaseDimensions()
    {
        return [self::$size, self::$size];
    }

    /**
     * @param bool $adminLog
     * @return $this
     */
    public function setAdminLog(bool $adminLog)
    {
        $this->adminLog = $adminLog;

        return $this;
    }

    /**
     * @return mixed
     * @throws \XF\PrintableException
     */
    protected function doUpload()
    {
        if (!$this->validated && !$this->validate()) {
            throw new LogicException('Could not save avatar with has errors.');
        }

        $uploadFile = $this->upload;
        if ($uploadFile === null) {
            throw new InvalidArgumentException('Must be set file');
        }

        $imageManager = $this->app->imageManager();
        $baseFile = $uploadFile->getTempFile();

        $shortSide = min($this->width, $this->height);
        if ($shortSide > static::$size) {
            $image = $imageManager->imageFromFile($baseFile);
            if ($image === null) {
                throw new InvalidArgumentException('Cannot image from file');
            }

            $image->resizeShortEdge(static::$size);

            $newTempFile = \XF\Util\File::getTempFile();
            if ($newTempFile && $image->save($newTempFile)) {
                $baseFile = $newTempFile;
            } else {
                throw new RuntimeException('Failed to save image to temporary file; check internal_data/data permissions');
            }

            unset($image);
        }

        $image = $imageManager->imageFromFile($baseFile);
        if ($image === null) {
            throw new InvalidArgumentException('Cannot image from file');
        }

        $this->resizeAvatarImage($image);
        $newTempFile = \XF\Util\File::getTempFile();

        if ($newTempFile !== false && $image->save($newTempFile)) {
            // good.
        } else {
            throw new RuntimeException('Failed to save image to temporary file; check internal_data/data permissions');
        }

        $group = $this->group;
        if ($group->AvatarAttachment !== null) {
            $group->AvatarAttachment->delete();
        }

        $fileWrapper = new FileWrapper($newTempFile, $uploadFile->getFileName());
        /** @var \XF\Service\Attachment\Preparer $attachmentPreparer */
        $attachmentPreparer = $this->service('XF:Attachment\Preparer');

        /** @var Attachment $attachmentRepo */
        $attachmentRepo = $this->repository('XF:Attachment');
        $handler = $attachmentRepo->getAttachmentHandler(App::CONTENT_TYPE_GROUP);

        if ($handler === null) {
            throw new InvalidArgumentException('Invalid handler');
        }

        $tempHash = md5(uniqid('', true));
        $attachment = $attachmentPreparer->insertAttachment($handler, $fileWrapper, XF::visitor(), $tempHash);

        $group->avatar_attachment_id = $attachment->attachment_id;
        $group->save();

        $attachmentPreparer->associateAttachmentsWithContent(
            $tempHash,
            App::CONTENT_TYPE_GROUP,
            $group->group_id
        );

        return $group;
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    public function delete()
    {
        if ($this->group->AvatarAttachment === null) {
            throw new LogicException('Group did not have custom avatar');
        }

        $this->group->AvatarAttachment->delete();

        $this->group->avatar_attachment_id = 0;
        $this->group->saveIfChanged();
    }

    /**
     * @param \XF\Image\AbstractDriver $image
     * @return void
     */
    protected function resizeAvatarImage(\XF\Image\AbstractDriver $image)
    {
        $image->resizeShortEdge(static::$size, true);

        $cropX = max(0, floor(($image->getWidth() - static::$size) / 2));
        $cropY = max(0, floor(($image->getHeight() - static::$size) / 2));

        $image->crop(static::$size, static::$size, $cropX, $cropY);
    }
}