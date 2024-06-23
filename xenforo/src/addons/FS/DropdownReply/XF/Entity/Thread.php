<?php

namespace FS\DropdownReply\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['dropdwon_options'] =  ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null];
        $structure->columns['is_dropdown_active'] =  ['type' => self::UINT, 'default' => 0];

        return $structure;
    }
    
    public function getPostCount() {

        if ($this->is_dropdown_active==1){
            $post = $this->Finder('XF:Post')->where('thread_id',$this->thread_id)->where('user_id',\XF::visitor()->user_id)->where('message_state','!=','deleted')->fetch();
            if ($this->user_id == \XF::visitor()->user_id){
		        return (count($post)-1);
            
            }
            else{
                return count($post);
            }
        }
        else{
             return 1;
        }
    
    }

}