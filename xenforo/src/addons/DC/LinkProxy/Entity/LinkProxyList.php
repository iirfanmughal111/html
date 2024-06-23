<?php

namespace DC\LinkProxy\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class LinkProxyList extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_link_Proxy_list';
        $structure->shortName = 'DC\LinkProxy:LinkProxyList';
        $structure->contentType = 'dc_link_proxy_list';
        $structure->primaryKey = 'list_id';
        $structure->columns = [
            'list_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_group_id' => ['type' => self::UINT, 'required' => true,'default' => NULL],
            'redirect_time' => ['type' => self::UINT, 'required' => true,'default' => NULL],
            'link_redirect_html' => ['type' => self::STR, 'default' => ''],
        
        ];

        $structure->relations = [
            'UserGroup' => [
                'entity' => 'XF:UserGroup',
                'type' => self::TO_ONE,
                'conditions' => 'user_group_id',
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }

    public function getUserGroupTime(){
        return $this->redirect_time;
    }
}