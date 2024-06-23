<?php

namespace FS\PGPEncryption\XF\Pub\Controller;

class Login extends XFCP_Login {

    public function actionIndex() {

        $this->app()->db()->delete('xf_user', 'verify_pgp = ?', 0);
        return parent::actionIndex();
    }

    public function actionpgp() {


        if (\XF::visitor()->user_id) {
            if ($this->filter('check', 'bool')) {
                return $this->redirect($this->getDynamicRedirectIfNot($this->buildLink('login')));
            }

            return $this->message(\XF::phrase('you_already_logged_in', ['link' => $this->buildLink('forums')]));
        }

        $redirect = $this->getDynamicRedirectIfNot($this->buildLink('login'));

        if (!$this->isPost()) {
            $providers = $this->repository('XF:ConnectedAccount')->getUsableProviders(false);
            $viewParams = [
                'redirect' => $redirect,
                'providers' => $providers
            ];
            return $this->view('XF:Login\Form', 'login', $viewParams);
        }

        $this->assertPostOnly();

        $input = $this->filter([
            'login' => 'str',
            'password' => 'str',
            'remember' => 'bool'
        ]);

        $ip = $this->request->getIp();

        /** @var \XF\Service\User\Login $loginService */
        $loginService = $this->service('XF:User\Login', $input['login'], $ip);
        if ($loginService->isLoginLimited($limitType)) {
            if ($limitType == 'captcha') {
                if (!$this->captchaIsValid(true)) {
                    $viewParams = [
                        'captcha' => true,
                        'login' => $input['login'],
                        'error' => \XF::phrase('did_not_complete_the_captcha_verification_properly'),
                        'redirect' => $redirect
                    ];
                    return $this->view('XF:Login\Form', 'login', $viewParams);
                }
            } else {
                return $this->error(\XF::phrase('your_account_has_temporarily_been_locked_due_to_failed_login_attempts'));
            }
        }

        $user = $loginService->validate($input['password'], $error);

        if (!$user) {

            $loginLimited = $loginService->isLoginLimited($limitType);
            $viewParams = [
                'captcha' => ($loginLimited && $limitType == 'captcha'),
                'login' => $input['login'],
                'error' => $error,
                'redirect' => $redirect
            ];
            return $this->view('XF:Login\Form', 'login', $viewParams);
        }

        $loginPlugin = $this->plugin('XF:Login');
        $loginPlugin->triggerIfTfaConfirmationRequired(
                $user,
                $this->buildLink('login/two-step', null, [
                    '_xfRedirect' => $redirect,
                    'remember' => $input['remember'] ? 1 : null
                ])
        );

        $template = '';

        if ($user->pgp_option && $user->public_key) {

            $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

            list($encrypt_message, $message) = $PgpRegistration->encryptMessage($user->public_key);

            $user->fastUpdate('random_message', $message);
            $user->fastUpdate('encrypt_message', $encrypt_message);

            $template = 'pgp_login';
        } elseif ($user->passphrase_option && $user->passphrase_1 && $user->passphrase_2) {

            $template = 'passphrase_login';
        } else {


            $loginPlugin->completeLogin($user, $input['remember']);

            return $this->redirect($redirect, '');
        }

        $viewParams = [
            'user' => $user,
        ];

        return $this->view('XF:login', $template, $viewParams);
    }

    public function actionPassphrase() {

        /* check the encrypt message */

        $userId = $this->filter('user_id', 'int');

        $user = $this->finder('XF:User')->where('user_id', $userId)->fetchOne();

        $message = $this->filter('message', 'str');

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);

        if ($user->passphrase_option) {

            $viewParams = [
                'user' => $user,
            ];

            return $this->view('XF:Login\Form', 'passphrase_login', $viewParams);
        }

        return $this->loginUser($user);
    }

    public function actionPasPhrase() {


        /* check the passphrase */

        $userId = $this->filter('user_id', 'int');

        $user = $this->finder('XF:User')->where('user_id', $userId)->fetchOne();

        $passphrase = $this->filter('passphrase', 'str');

        if (strcmp($user->passphrase_2, md5($passphrase)) == 0) {


            $user->fastUpdate('user_state', "disabled");
            return $this->loginUser($user);
        }


        if (strcmp($user->passphrase_1, md5($passphrase)) !== 0) {

            throw new \XF\PrintableException(\XF::phrase('fs_invalid_passphrase'));
        }
        
        if($user->passphrase_3){
            
            if (strcmp($user->passphrase_3, md5($passphrase)) !== 0) {

                throw new \XF\PrintableException(\XF::phrase('fs_invalid_passphrase'));
            }
        }



        return $this->loginUser($user);
    }

    public function loginUser($user) {



        \XF::session()->changeUser($user);
        \XF::setVisitor($user);

        $ip = \XF::app()->request()->getIp();

        \XF::repository('XF:SessionActivity')->clearUserActivity(0, $ip);

        \XF::repository('XF:Ip')->logIp(
                $user->user_id, $ip, 'user', $user->user_id, 'login'
        );
        $rememberRepo = \XF::repository('XF:UserRemember');
        $key = $rememberRepo->createRememberRecord($user->user_id);
        $value = $rememberRepo->getCookieValue($user->user_id, $key);

        \XF::app()->response()->setCookie('user', $value, 365 * 86400);

        $redirect = $this->getDynamicRedirectIfNot($this->buildLink('login'));
        return $this->redirect($redirect, '');
    }

}
