<?php

namespace Z61\Classifieds\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null condition_id
 * @property int display_order
 * @property bool active
 *
 * GETTERS
 * @property \XF\Phrase title
 *
 * RELATIONS
 * @property \XF\Entity\Phrase MasterTitle
 */
class Condition extends Entity
{
    /**
     * @return \XF\Phrase
     */
    public function getTitle()
    {
        return \XF::phrase($this->getPhraseName());
    }

    public function getPhraseName()
    {
        return 'z61_condition_title.' . $this->condition_id;
    }

    public function getMasterPhrase()
    {
        $phrase = $this->MasterTitle;

        if (!$phrase)
        {
            $phrase = $this->_em->create('XF:Phrase');
            $phrase->title = $this->_getDeferredValue(function() { return $this->getPhraseName(); }, 'save');
            $phrase->language_id = 0;
            $phrase->addon_id = '';
        }

        return $phrase;
    }

    protected function _postDelete()
    {
        if ($this->MasterTitle)
        {
            $this->MasterTitle->delete();
        }

        // TODO: delete listings with this condition?
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_condition';
        $structure->shortName = 'Z61\Classifieds:Condition';
        $structure->primaryKey = 'condition_id';
        $structure->columns = [
            'condition_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'display_order' => ['type' => self::UINT, 'default' => 0],
            'active' => ['type' => self::BOOL, 'default' => true],
        ];
        $structure->behaviors = [];
        $structure->getters = [
            'title' => true,
        ];
        $structure->relations = [
            'MasterTitle' => [
                'entity' => 'XF:Phrase',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['language_id', '=', 0],
                    ['title', '=', 'z61_condition_title.', '$condition_id']
                ]
            ]
        ];

        return $structure;
    }

    /**
     * @return \Z61\Classifieds\Repository\Condition
     */
    protected function getConditionRepo()
    {
        return $this->repository('Z61\Classifieds:Condition');
    }
}