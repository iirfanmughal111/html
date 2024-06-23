<?php

namespace FS\QuestionAnswers\XF\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['question_count'] = ['type' => self::UINT, 'default' => 0];
        $structure->columns['answer_count'] = ['type' => self::UINT, 'default' => 0];

        return $structure;
    }
}
