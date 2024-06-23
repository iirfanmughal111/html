<?php

namespace Truonglv\Groups\Service\ResourceItem;

use XF;
use Truonglv\Groups\App;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\ResourceItem;

class Preparer extends AbstractService
{
    /**
     * @var ResourceItem
     */
    protected $resource;

    /**
     * @var string|null
     */
    protected $attachmentHash;
    /**
     * @var string|null
     */
    protected $iconUrl;
    /**
     * @var \XF\Http\Upload|null
     */
    protected $iconFile;
    /**
     * @var Icon|null
     */
    protected $iconService;

    public function __construct(\XF\App $app, ResourceItem $resource)
    {
        parent::__construct($app);

        $this->resource = $resource;
    }

    /**
     * @param string $attachmentHash
     * @return void
     */
    public function setAttachmentHash($attachmentHash)
    {
        $this->attachmentHash = $attachmentHash;
    }

    /**
     * @param string $iconUrl
     */
    public function setIconUrl(string $iconUrl): void
    {
        $this->iconUrl = $iconUrl;
    }

    /**
     * @param \XF\Http\Upload $iconFile
     */
    public function setIconFile(\XF\Http\Upload $iconFile): void
    {
        $this->iconFile = $iconFile;
    }

    /**
     * @return void
     */
    public function finalizeSetup()
    {
    }

    /**
     * @param array $errors
     * @return void
     */
    public function validate(array & $errors = [])
    {
        $this->validateIcon($errors);

        $attachCount = 0;
        if ($this->attachmentHash !== null) {
            $attachCount += $this->finder('XF:Attachment')
                ->where('temp_hash', $this->attachmentHash)
                ->where('content_type', App::CONTENT_TYPE_RESOURCE)
                ->total();
        }

        if ($this->resource->resource_id > 0) {
            $attachCount += $this->finder('XF:Attachment')
                ->where('content_id', $this->resource->resource_id)
                ->where('content_type', App::CONTENT_TYPE_RESOURCE)
                ->where('unassociated', 0)
                ->total();
        }

        $requiredFiles = App::getOption('resourceRequiredFiles') > 0;
        if ($attachCount === 0 && $requiredFiles) {
            $errors[] = XF::phrase('tlg_resource_must_have_a_file');
        }
    }

    /**
     * @return void
     */
    public function postInsert()
    {
        $this->associateAttachments();
        if ($this->iconService !== null) {
            $this->iconService->save();
        }
    }

    /**
     * @return void
     */
    public function postUpdate()
    {
        $this->associateAttachments();
    }

    protected function validateIcon(array & $errors = []): void
    {
        $iconService = null;
        if ($this->iconFile !== null) {
            $upload = $this->iconFile;
            /** @var Icon $iconService */
            $iconService = $this->app->service('Truonglv\Groups:ResourceItem\Icon', $this->resource);
            $iconService->setUploadFile($upload);
        } elseif ($this->iconUrl !== null) {
            /** @var Icon $iconService */
            $iconService = $this->app->service('Truonglv\Groups:ResourceItem\Icon', $this->resource);
            $iconService->setIconUrl($this->iconUrl);
        }

        if ($iconService !== null) {
            $iconService->validate($errors);
            $this->iconService = $iconService;
        }
    }

    /**
     * @return void
     */
    protected function associateAttachments()
    {
        if ($this->attachmentHash === null) {
            return;
        }

        /** @var \XF\Service\Attachment\Preparer $preparer */
        $preparer = $this->service('XF:Attachment\Preparer');
        $total = $preparer->associateAttachmentsWithContent(
            $this->attachmentHash,
            App::CONTENT_TYPE_RESOURCE,
            $this->resource->resource_id
        );

        if ($total > 0) {
            $this->resource->fastUpdate('attach_count', $this->resource->attach_count + $total);
        }
    }
}
