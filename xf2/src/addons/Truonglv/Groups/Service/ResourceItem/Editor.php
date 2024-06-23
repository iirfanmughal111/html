<?php

namespace Truonglv\Groups\Service\ResourceItem;

use XF\Service\AbstractService;
use XF\Service\ValidateAndSavableTrait;
use Truonglv\Groups\Entity\ResourceItem;
use Truonglv\Groups\Service\CommentableTrait;

class Editor extends AbstractService
{
    use ValidateAndSavableTrait, CommentableTrait;

    /**
     * @var ResourceItem
     */
    protected $resource;
    /**
     * @var Preparer
     */
    protected $preparer;

    public function __construct(\XF\App $app, ResourceItem $resource)
    {
        parent::__construct($app);

        $this->resource = $resource;

        /** @var Preparer $preparer */
        $preparer = $this->service('Truonglv\Groups:ResourceItem\Preparer', $resource);
        $this->preparer = $preparer;

        $comment = $resource->FirstComment;
        $this->setupCommentDefaults($comment);
        $this->resource->addCascadedSave($comment);
    }

    /**
     * @return Preparer
     */
    public function getPreparer()
    {
        return $this->preparer;
    }

    /**
     * @return ResourceItem
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return void
     */
    protected function finalizeSetup()
    {
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->finalizeSetup();
        $this->preparer->finalizeSetup();

        $this->resource->preSave();
        $errors = $this->resource->getErrors();
        $this->preparer->validate($errors);

        return $errors;
    }

    /**
     * @return ResourceItem
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $db->beginTransaction();

        $this->resource->save(true, false);

        $this->preparer->postUpdate();

        $db->commit();

        return $this->resource;
    }
}
