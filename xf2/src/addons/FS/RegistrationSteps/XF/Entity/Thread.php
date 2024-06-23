<?php

namespace FS\RegistrationSteps\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
	public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['review_for'] =  ['type' => self::UINT, 'default' => 0];
        $structure->columns['is_featured'] =  ['type' => self::UINT, 'default' => 0];


        $structure->relations += [
			'ReviewFor' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' =>  [
                    ['user_id', '=', '$review_for']
                ],
            ],
	
        ];

        return $structure;
    }


    public function getCompaninionThreadIds(){
		$compainion_ids = explode(',',\XF::app()->options()->fs_register_compainion_ids);	
		return  in_array($this->node_id,$compainion_ids);
	}

	public function getReviewsThreadIds(){
		$reviews_ids = explode(',',\XF::app()->options()->fs_register_Reviews_ids);
		return  in_array($this->node_id,$reviews_ids);
	}
}