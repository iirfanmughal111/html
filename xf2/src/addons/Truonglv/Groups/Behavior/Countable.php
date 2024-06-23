<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Behavior;

use LogicException;
use function get_class;
use XF\Mvc\Entity\Behavior;

class Countable extends Behavior
{
    /**
     * @return array
     */
    protected function getDefaultConfig()
    {
        return [
            'relationName' => '',
            'relationKey' => '',
            'countField' => '',
            'stateField' => '',
            'visibleState' => 'visible'
        ];
    }

    /**
     * @return void
     */
    protected function verifyConfig()
    {
        if ($this->config['relationName'] === '') {
            throw new LogicException('Relation name must be set.');
        }

        if ($this->config['relationKey'] === '') {
            throw new LogicException('Relation key must be set.');
        }

        if (!$this->entity->isValidRelation($this->config['relationName'])) {
            throw new LogicException(sprintf(
                '(%s) relation not found in entity (%s)',
                $this->config['relationName'],
                get_class($this->entity)
            ));
        }

        if ($this->config['countField'] === '') {
            throw new LogicException('Count field must be set in (' . get_class($this->entity) . ')');
        }
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    public function postSave()
    {
        if ($this->config['stateField'] !== '') {
            $visibleChange = $this->entity->isStateChanged($this->config['stateField'], $this->config['visibleState']);
            if ($visibleChange === 'enter') {
                $this->updateCount($this->getRelationEntity(), 1);
            } elseif ($visibleChange === 'leave') {
                $this->updateCount($this->getRelationEntity(), -1);
            }
        } else {
            if ($this->entity->isInsert()) {
                $entity = $this->getRelationEntity();
                $this->updateCount($entity, 1);
            } elseif ($this->entity->isUpdate()
                && $this->entity->isChanged($this->config['relationKey'])
            ) {
                $existEntity = $this->entity->getExistingRelation($this->config['relationName']);
                $this->updateCount($existEntity, -1);

                $entity = $this->getRelationEntity();
                $this->updateCount($entity, 1);
            }
        }
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    public function postDelete()
    {
        if (!$this->entity->get($this->config['relationKey'])) {
            return;
        }

        if ($this->config['stateField'] !== '') {
            if ($this->entity->get($this->config['stateField']) === $this->config['visibleState']) {
                $this->updateCount($this->getRelationEntity(), -1);
            }
        } else {
            $entity = $this->getRelationEntity();
            $this->updateCount($entity, -1);
        }
    }

    /**
     * @param \XF\Mvc\Entity\Entity|null $entity
     * @param int $adjust
     * @throws \XF\PrintableException
     * @return void
     */
    protected function updateCount($entity, $adjust)
    {
        if ($entity === null || $entity->isDeleted()) {
            return;
        }

        $entity->set($this->config['countField'], $entity->get($this->config['countField']) + $adjust);
        $entity->save();
    }

    /**
     * @return \XF\Mvc\Entity\Entity
     */
    public function getRelationEntity()
    {
        return $this->entity->getRelation($this->config['relationName']);
    }
}
