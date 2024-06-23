<?php

namespace FS\UpdateUserId\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class User extends XFCP_User
{
    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if ($params->user_id) {
            $user = $this->assertUserExists($params->user_id);
            $this->assertCanEditUser($user);
        } else {
            $user = null;
        }

        $user = $this->getUserRepo()->setupBaseUser($user);
        $this->userSaveProcess($user)->run();

        if (!$params->user_id) {
        
            $userUpdate = $this->service('FS\UpdateUserId:UpdateUser');
            $newId = $userUpdate->RandomId();

            $lastUserId = $user->user_id;
            $user->fastUpdate('user_id', $newId);

           
            $userUpdate->userOption($newId, $lastUserId);
            $userUpdate->userPrivacy($newId, $lastUserId);
            $userUpdate->userProfile($newId, $lastUserId);
            $userUpdate->userAuthenticate($newId, $lastUserId);
            $userUpdate->userGroupRelation($newId, $lastUserId);;
        }
        return $this->redirect($this->buildLink('users/search', null, ['last_user_id' => $user->user_id]));
    }

   
}
