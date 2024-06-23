<?php

namespace FS\HideUsernames\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{

    public function getUsername()
    {
        $username = $this->username_;
        $user_id = $this->user_id;
        $randomName = $this->random_name_;

        $visitor = \XF::visitor();

        if ($visitor->user_id == $user_id or $visitor->is_admin or $visitor->is_moderator) {
            return $username;
        }

    if (!$this->hasPermission('fs_user_names', 'hide')) {
            return $username;
        }


    if ($visitor->hasPermission('fs_user_names', 'can_see_usernames')) {
            return $username;
        }


        $options = $this->app()->options();
        if(trim($options->fs_unhide_user_ids))
        {
            

                $userIds = explode(",", $options->fs_unhide_user_ids);

                if (in_array($visitor->user_id, $userIds)) {
                    return $username;
                }
        }

    



        return $randomName;
    }


            protected function _postSave()
                {


            $length = rand(4, 6); // Generate a random length between 4 and 6

                        $randomName = ucwords(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, $length));

                        $this->fastUpdate('random_name', $randomName);

            parent::_postSave();


            }




    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['random_name'] =  ['type' => self::STR, 'default' => null];

        $structure->getters += [
            'username' => ['getter' => 'getUsername', 'cache' => false],
        ];

        return $structure;
    }
}