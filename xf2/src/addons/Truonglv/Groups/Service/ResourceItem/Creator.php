<?php

namespace Truonglv\Groups\Service\ResourceItem;

use XF;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;
use XF\Service\ValidateAndSavableTrait;
use Truonglv\Groups\Entity\ResourceItem;
use Truonglv\Groups\Service\CommentableTrait;

class Creator extends AbstractService
{
    use ValidateAndSavableTrait, CommentableTrait;

    /**
     * @var Group
     */
    protected $group;
    /**
     * @var \XF\Entity\User
     */
    protected $user;
    /**
     * @var ResourceItem
     */
    protected $resource;
    /**
     * @var Preparer
     */
    protected $preparer;

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        $this->group = $group;
        $this->user = XF::visitor();

        /** @var ResourceItem $resource */
        $resource = $app->em()->create('Truonglv\Groups:ResourceItem');
        $this->resource = $resource;

        /** @var Preparer $preparer */
        $preparer = $this->service('Truonglv\Groups:ResourceItem\Preparer', $resource);
        $this->preparer = $preparer;

        $this->setupDefaults();
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        $this->setupCommentDefaults();

        $this->comment->setGroup($this->group);
        $this->resource->addCascadedSave($this->comment);
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
        $resource = $this->resource;

        $resource->group_id = $this->group->group_id;
        $resource->user_id = $this->user->user_id;
        $resource->username = $this->user->username;

        $this->setupComment(
            $this->user,
            $resource->getCommentContentType(),
            0
        );
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->finalizeSetup();
        $this->preparer->finalizeSetup();

        $resource = $this->resource;
        $resource->preSave();

        $errors = $resource->getErrors();
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

        $resource = $this->resource;
        $comment = $this->comment;

        $resource->save(true, false);
        $comment->fastUpdate('content_id', $resource->resource_id);

        $resource->fastUpdate([
            'first_comment_id' => $this->comment->comment_id
        ]);

        $this->preparer->postInsert();
        $this->commentPreparer->afterInsert();

        $db->commit();

        return $this->resource;
    }
}
