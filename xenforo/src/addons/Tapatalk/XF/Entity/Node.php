<?php

namespace Tapatalk\XF\Entity;

class Node extends XFCP_Node
{
    /**
     * @return bool
     */
    protected static function inMbq()
    {
        return defined('MBQ_IN_IT') ? true : false;
    }

    /**
     * @return array|mixed
     */
    protected static function getHideForums()
    {
        $hideForums = \XF::app()->options()->hideForums;
        if (!is_array($hideForums)) {
            $hideForums = unserialize($hideForums);
        }
        if (!is_array($hideForums)) $hideForums = [];
        return $hideForums;
    }

    public function canView(&$error = null)
    {
        if (!self::inMbq()) {
            return parent::canView($error); // TODO: Change the autogenerated stub
        }

        /** @var AbstractNode $data */
        $data = $this->Data;
        if (!$data)
        {
            return false;
        }

        $hideForums = self::getHideForums();
        if ($hideForums && isset($data->node_id) && in_array($data->node_id, $hideForums)) {
            return false;
        }

        return $data->canView($error);

    }

    /**
     * @param \XF\Mvc\Entity\Structure $structure
     * @return \XF\Mvc\Entity\Structure
     */
    public static function getStructure(\XF\Mvc\Entity\Structure $structure)
    {
        $structure = parent::getStructure($structure); // TODO: Change the autogenerated stub

        $structure->relations += [
            'Forum' => [
                'entity' => 'XF:Forum',
                'type' => self::TO_ONE,
                'conditions' => 'node_id',
                'key' => 'node_id',
                'primary' => true
            ]
        ];

        return $structure;
    }

}