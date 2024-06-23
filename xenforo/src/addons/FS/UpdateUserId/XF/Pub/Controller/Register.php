<?php

namespace FS\UpdateUserId\XF\Pub\Controller;

use XF\Entity\User as EntityUser;

class Register extends XFCP_Register
{
    protected function finalizeRegistration(EntityUser $user)
    {
            $userUpdate = $this->service('FS\UpdateUserId:UpdateUser');
            $newId = $userUpdate->RandomId();

            $lastUserId = $user->user_id;
            $user->fastUpdate('user_id', $newId);

           
            $userUpdate->userOption($newId, $lastUserId);
            $userUpdate->userPrivacy($newId, $lastUserId);
            $userUpdate->userProfile($newId, $lastUserId);
            $userUpdate->userAuthenticate($newId, $lastUserId);
            $userUpdate->userGroupRelation($newId, $lastUserId);;
            return parent::finalizeRegistration($user);
    }
    
    
}
