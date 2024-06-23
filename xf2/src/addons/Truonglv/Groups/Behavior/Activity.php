<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Behavior;

use Closure;
use function count;
use LogicException;
use function is_string;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Behavior;

class Activity extends Behavior
{
    /**
     * @return array
     */
    protected function getDefaultConfig()
    {
        return [
            'groupIdField' => 'group_id',
            'stateField' => null,
            'checkForUpdates' => []
        ];
    }

    /**
     * @return void
     */
    protected function verifyConfig()
    {
        $groupIdField = $this->config['groupIdField'];
        if (is_string($groupIdField) && !$this->entity->isValidColumn($groupIdField)) {
            throw new LogicException('Columns (' . $groupIdField . ') does not exists!');
        }
    }

    /**
     * @return void
     */
    public function postSave()
    {
        $groupId = $this->getGroupId();
        if ($groupId <= 0) {
            return;
        }

        $isVisible = $this->config['stateField'] !== null
            ? ($this->entity->get($this->config['stateField']) === 'visible')
            : true;
        $checkForUpdates = $this->config['checkForUpdates'];
        if ($this->entity->isInsert() && $isVisible
            || (count($checkForUpdates) > 0 && $this->entity->isChanged($checkForUpdates))
        ) {
            App::groupRepo()->logGroupActivity($groupId);
        }
    }

    /**
     * @return int
     */
    protected function getGroupId()
    {
        if ($this->config['groupIdField'] instanceof Closure) {
            return $this->config['groupIdField']($this->entity);
        }

        return $this->entity->getValue($this->config['groupIdField']);
    }
}
