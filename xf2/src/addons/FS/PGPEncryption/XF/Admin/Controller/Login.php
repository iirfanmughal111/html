<?php

namespace FS\PGPEncryption\XF\Admin\Controller;

use XF\ControllerPlugin\LoginTfaResult;
use XF\Mvc\ParameterBag;

class Login extends XFCP_Login {

    public function actionLogin() {

        $message = $this->filter('message', 'str');
        $userId = $this->filter('user_id', 'str');

        if ($message && $userId) {

            $userPGP = $this->finder('XF:User')->where('user_id', $userId)->fetchOne();
            $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
            $PgpRegistration->CheckEncryptMsg($userPGP->random_message, $message);
            $redirect = $this->getDynamicRedirectIfNot($this->buildLink('login'));

            $this->completeLogin($userPGP);

            return $this->redirect($redirect, '');
        }


        $input = $this->filter([
            'login' => 'str',
            'password' => 'str'
        ]);

        $ip = $this->request->getIp();

        /** @var \XF\Service\User\Login $loginService */
        $loginService = $this->service('XF:User\Login', $input['login'], $ip);
        if ($loginService->isLoginLimited($limitType)) {
            return $this->error(\XF::phrase('your_account_has_temporarily_been_locked_due_to_failed_login_attempts'));
        }

        $user = $loginService->validate($input['password'], $error);

        if (!$user) {
            return $this->error($error);
        }


        $enableAdminKey = \xf::options()->fs_admin_public_key;

        if (isset($enableAdminKey['enable_admin_pgp']) && $enableAdminKey['enable_public_key'] = "enable_public_key") {

            $publicKey = $enableAdminKey['public_key'];

            $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

            list($encrypt_message, $message) = $PgpRegistration->encryptMessage($publicKey);

            $user->fastUpdate('random_message', $message);
            $user->fastUpdate('encrypt_message', $encrypt_message);

            $viewParams = ['user' => $user];
            return $this->view('XF:Login\login', 'admin_pgp_encryption', $viewParams);
        }


        return parent::actionLogin();
    }

}
