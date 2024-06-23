<?php

namespace Z61\Classifieds\XF\Entity;

use XF\Mvc\Entity\Structure;
use Z61\Classifieds\Entity\Category;

/**
 * Class Forum
 * @package Z61\Classifieds\XF\Entity
 * @property Category ClassifiedsCategory
 */
class Forum extends XFCP_Forum
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns += [
            'z61c_replace_action_btn' => ['type' => self::BOOL, 'default' => true],
        ];

        $structure->relations += [
            'ClassifiedsCategories' => [
                'entity' => 'Z61\Classifieds:Category',
                'type' => self::TO_MANY,
                'conditions' => 'node_id',
                'primary' => false
            ]
        ];

        // Will have to check how much of an impact this has before enabling.
        //$structure->defaultWith += ['ClassifiedCategories'];

        return $structure;
    }
}