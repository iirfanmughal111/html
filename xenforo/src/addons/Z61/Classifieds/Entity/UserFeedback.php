<?php

namespace Z61\Classifieds\Entity;


use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class UserFeedback extends Entity
{
    protected function _preSave()
    {
        if (!$this->isChanged('total') && ( $this->isChanged('positive') || $this->isChanged('negative')))
        {
            $total = $this->get('positive') - $this->get('negative');
            $this->set('total', $total);
        }
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_user_feedback';
        $structure->shortName = 'Z61\Classifieds:UserFeedback';
        $structure->primaryKey = 'user_id';
        $structure->columns = [
            'user_id' => ['type' => self::UINT, 'required' => true],
            'positive' => ['type' => self::UINT, 'default' => 0],
            'neutral' => ['type' => self::UINT, 'default' => 0],
            'negative' => ['type' => self::UINT, 'default' => 0],
            'total' => ['type' => self::INT, 'default' => 0],
            'last_feedback_date' => ['type' => self::UINT, 'default' => \XF::$time]
        ];

        return $structure;
    }
}