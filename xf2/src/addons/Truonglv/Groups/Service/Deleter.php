<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service;

use XF;
use LogicException;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Service\AbstractService;

class Deleter extends AbstractService
{
    /**
     * @var Entity
     */
    protected $entity;
    /**
     * @var string|null
     */
    protected $stateField;

    public function __construct(\XF\App $app, Entity $entity)
    {
        parent::__construct($app);

        $this->entity = $entity;
    }

    /**
     * @param string $stateField
     * @return $this
     */
    public function setStateField(string $stateField)
    {
        $this->stateField = $stateField;

        return $this;
    }

    /**
     * @param bool $hardDelete
     * @param string $reason
     * @return void
     *@throws \XF\PrintableException
     */
    public function delete($hardDelete, $reason = '')
    {
        if ($hardDelete) {
            $this->entity->delete();
        } else {
            $entity = $this->entity;
            if ($this->stateField === null) {
                throw new LogicException('Must be set stateField.');
            }

            $entity->set($this->stateField, App::STATE_DELETED);

            /** @var \XF\Entity\DeletionLog $deletionLog */
            $deletionLog = $entity->getRelationOrDefault('DeletionLog');
            $deletionLog->setFromUser(XF::visitor());
            $deletionLog->delete_reason = $reason;

            $entity->save();
        }
    }
}
