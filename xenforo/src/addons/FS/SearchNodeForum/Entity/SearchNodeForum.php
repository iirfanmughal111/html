<?php

namespace FS\SearchNodeForum\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Util\Arr;
use XF\Entity\BookmarkTrait;
use XF\Entity\LinkableInterface;


class SearchNodeForum extends Entity 
{


    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_search_Node';
        $structure->shortName = 'FS\SearchNodeForum:SearchNodeForum';
        $structure->contentType = 'fs_searchNode';
        $structure->primaryKey = 'search_id';
        $structure->relations = [ 
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }

    

      
}