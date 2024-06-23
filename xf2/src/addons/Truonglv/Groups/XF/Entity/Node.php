<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */
 
namespace Truonglv\Groups\XF\Entity;

use Closure;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Structure;
use Truonglv\Groups\Entity\Group;

/**
 * Class Node
 * @package Truonglv\Groups\XF\Entity
 *
 * @inheritdoc
 *
 * @property \Truonglv\Groups\Entity\Group|null $GroupEntity
 *
 * @property string $title_
 */
class Node extends XFCP_Node
{
    /**
     * @var null|array
     */
    protected static $orgGetterTitleCallback = null;

    /**
     * @return string
     */
    public function getTitle()
    {
        $callable = ['parent', 'getTitle'];
        if (is_callable($callable)) {
            $title = call_user_func($callable);
        } elseif (is_array(self::$orgGetterTitleCallback)) {
            if (self::$orgGetterTitleCallback[0] === '$this') {
                self::$orgGetterTitleCallback[0] = $this;
            }

            /** @var mixed $callable */
            $callable = self::$orgGetterTitleCallback;
            if (is_callable($callable)) {
                $title = call_user_func($callable);
            } else {
                $title = $this->title_;
            }
        } else {
            $title = $this->title_;
        }

        $group = $this->getTlgGroupEntity();
        if (!App::$isAppendGroupNameIntoNodeTitle
            || !App::isEnabledForums()
            || $group === null
        ) {
            return $title;
        }

        $format = trim(App::getOption('nodeTitleFormat'));
        if (!$this->hasChanges() && $format !== '') {
            $title = strtr($format, [
                '{title}' => $title,
                '{name}' => $group->name
            ]);
        }

        return $title;
    }

    /**
     * @param Group|null $group
     * @return void
     */
    public function setTlgGroupEntity(Group $group = null)
    {
        $this->_getterCache['GroupEntity'] = $group;
    }

    /**
     * @return Group|null
     */
    public function getTlgGroupEntity()
    {
        if ($this->node_type_id !== App::NODE_TYPE_ID
            || !isset($this->_getterCache['GroupEntity'])
        ) {
            return null;
        }

        return $this->_getterCache['GroupEntity'];
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        if (App::isEnabledForums()) {
            if (isset($structure->getters['title'])
                && isset($structure->getters['title']['getter'])
            ) {
                if ($structure->getters['title']['getter'] === true) {
                    self::$orgGetterTitleCallback = ['parent', 'getTitle'];
                } else {
                    self::$orgGetterTitleCallback = ['$this', $structure->getters['title']['getter']];
                }
            }

            $structure->getters['title'] = true;
            $structure->getters['GroupEntity'] = [
                'cache' => false,
                'getter' => 'getTlgGroupEntity'
            ];

            $structure->options[App::OPTION_MANUAL_REBUILD_PERMISSION] = null;
        }

        return $structure;
    }

    protected function _postSave()
    {
        parent::_postSave();

        if (!App::isEnabledForums()) {
            return;
        }

        if ($this->isInsert() || $this->isChanged('parent_node_id')) {
            $callback = $this->getOption(App::OPTION_MANUAL_REBUILD_PERMISSION);
            if ($callback instanceof Closure) {
                call_user_func($callback, $this);
            }
        }
    }
}
