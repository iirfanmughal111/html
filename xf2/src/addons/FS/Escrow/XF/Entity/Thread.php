<?php

namespace FS\Escrow\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['escrow_id'] =  ['type' => self::UINT, 'default' => 0];

        $structure->relations += [
            'Escrow' => [
                'entity' => 'FS\Escrow:Escrow',
                'type' => self::TO_ONE,
                'conditions' => 'escrow_id',
                'primary' => true
            ],
        ];

        return $structure;
    }
}
