<?php

namespace FS\ForumGroups\Service\ForumGroups;

use XF;
use function count;
use LogicException;
use XF\Http\Upload;
use function in_array;
use function is_array;
use function array_merge;
use function getimagesize;
use InvalidArgumentException;
use XF\Service\AbstractService;
use XF\Entity\Node;

abstract class AbstractFormUpload extends AbstractService
{
    /**
     * @var Node
     */
    protected $subCommunity;
    /**
     * @var Upload|null
     */
    protected $upload;
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
     * @var array
     */
    protected $allowImageTypes = [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF];
    /**
     * @var bool
     */
    private $validated = false;

    /**
     * @return string
     */
    abstract public function getFormAction();

    /**
     * @return \XF\Phrase
     */
    abstract public function getFormFieldLabel();

    /**
     * @return bool
     */
    abstract public function canDeleteExisting();

    /**
     * @return void
     */
    abstract public function delete();

    public function __construct(\XF\App $app, Node $subCommunity)
    {
        parent::__construct($app);

        $this->subCommunity = $subCommunity;
    }

    /**
     * @param Upload $upload
     * @return void
     */
    public function setUpload(Upload $upload)
    {
        $this->upload = $upload;
    }

    /**
     * @param array $errors
     * @return bool
     */
    final public function validate(& $errors = [])
    {
        $this->validated = true;

        $upload = $this->upload;
        if ($upload === null) {
            throw new LogicException('Must be set uploaded file.');
        }

        $upload->requireImage();
        if (!$upload->isValid($errors)) {
            return false;
        }

        $errors = [];

        $imageInfo = @getimagesize($upload->getTempFile());
        if (!is_array($imageInfo)) {
            $errors[] = XF::phrase('provided_file_is_not_valid_image');

            return false;
        }

        $width = (int) $imageInfo[0];
        $height = (int) $imageInfo[1];
        $type = (int) $imageInfo[2];
        if (count($this->allowImageTypes) > 0 && !in_array($type, $this->allowImageTypes, true)) {
            $errors[] = XF::phrase('provided_file_is_not_valid_image');
        } elseif (!$this->app->imageManager()->canResize($width, $height)) {
            $errors[] = XF::phrase('uploaded_image_is_too_big');
        }

        $this->width = $width;
        $this->height = $height;
        $this->type = $type;

        $errors = array_merge($errors, $this->_validate());

        return count($errors) === 0;
    }

    /**
     * @return mixed
     */
    final public function upload()
    {
        if (!$this->validated) {
            throw new InvalidArgumentException('Cannot save with validation errors. Use validate() to ensure there are no errors. ');
        }

        return $this->doUpload();
    }

    /**
     * @return array|null
     */
    public function getBaseDimensions()
    {
        return null;
    }

    /**
     * @return mixed
     */
    protected function doUpload()
    {
        throw new LogicException('Must be implemented!');
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        return [];
    }
}
