<?php

namespace FS\PGPEncryption\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;

class Member extends XFCP_Member {

    public function actionpublickey(ParameterBag $params) {

//        $user = \xf::visitor();
//
//        if (!$user->user_id) {
//
//            throw new \XF\PrintableException(\XF::phrase('fs_not_allowed'));
//        }
//        if ($user->user_id != $params->user_id) {
//
////            return $this->redirect($this->buildLink('members/public-key', $user));
//
//            throw new \XF\PrintableException(\XF::phrase('fs_not_allowed'));
//        }

        $user = $this->assertViewableUser($params->user_id);
        	$viewParams = [
			'user' => $user,

		];


        return $this->view('FS\PGPEncryption:Member', 'profile_public_key',$viewParams);
    }

}
