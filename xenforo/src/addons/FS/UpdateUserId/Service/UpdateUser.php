<?php

namespace FS\UpdateUserId\Service;

class UpdateUser extends \XF\Service\AbstractService
{

    public function RandomId()
    {
        $unix = \XF::$time;
        return substr($unix, 1, 10);
    }
    public function userGroupRelation($newId, $lastUserId)
    {
        \XF::db()->update(
            'xf_user_group_relation',
            ['user_id' => $newId],
            'user_id = ?',
            $lastUserId
        );
    }

    public function userOption($newId, $lastUserId)
    {
        $lastUserOption = $lastUserId ? $this->em()->find('XF:UserOption', $lastUserId) : null;

        $lastUserOption->fastUpdate('user_id', $newId);
    }

    public function userPrivacy($newId, $lastUserId)
    {
        $lastUserOption = $lastUserId ? $this->em()->find('XF:UserPrivacy', $lastUserId) : null;

        $lastUserOption->fastUpdate('user_id', $newId);
    }

    public function userProfile($newId, $lastUserId)
    {
        $lastUserOption = $lastUserId ? $this->em()->find('XF:UserProfile', $lastUserId) : null;

        $lastUserOption->fastUpdate('user_id', $newId);
    }

    public function userAuthenticate($newId, $lastUserId)
    {
        $lastUserOption = $lastUserId ? $this->em()->find('XF:UserAuth', $lastUserId) : null;

        $lastUserOption->fastUpdate('user_id', $newId);
    }
}
