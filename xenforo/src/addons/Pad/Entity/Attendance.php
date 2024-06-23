<?php 

namespace Pad\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;    


class Attendance extends Entity{
   public static function getStructure(Structure $structure){
    $structure->table = 'xf_forum_attendance';
    $structure->shortName = 'Pad:Attendance';

    // $structure->contentType = 'attendance_sheet';

    $structure->primaryKey = 'attendance_id';

    $structure->columns = [
        'attendance_id' =>['type' => self::UINT,'autoIncrement' =>true],
        'user_id' =>['type' => self::UINT,'default' =>\XF::visitor()->user_id],
        'date' =>['type' => self::STR,'default' =>\XF::$time],
        'in_time' =>['type' => self::UINT,'default' =>\XF::$time],
        'out_time' =>['type' => self::UINT,'default' =>\XF::$time],
        'comment' =>['type' => self::STR,'default' =>null],


    ];

    // $structure->relation = [
    //     'User' =>[
    //         'entity' =>'XF:User',
    //         'type'=>self::TO_ONE,
    //         'coditions'=>'user_id',
    //         'primary'=>true
    //     ],
    // ];
    $structure->relations = [
        'User' => [
            'entity' => 'XF:User',
            'type' => self::TO_ONE,
            'conditions' => 'user_id',
            'primary' => true
        ]
    ];


    $structure->defualtWith = ['User'];
    $structure->getters = [];
    $structure->beahaviors = [];

return $structure;

   }
}