<?php

namespace FS\PGPEncryption\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;

class LostPassword extends XFCP_LostPassword {

    public function actionIndex() {


        if ($this->isPost()) {

            $email = $this->filter('email', 'str');
            if ($email) {

                return parent::actionIndex();
            }
            $username = $this->filter('username', 'str');
            $user = $this->em()->findOne('XF:User', ['username' => $username]);

            if (!$user) {
                return $this->error(\XF::phrase('requested_member_not_found'));
            }

            if (!$user->public_key) {

                return $this->view('XF:LostPassword\Index', 'lost_password');
            }

            $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

            list($encrypt_message, $message) = $PgpRegistration->encryptMessage($user->public_key);

            $user->fastUpdate('random_message', $message);

            $user->fastUpdate('encrypt_message', $encrypt_message);

            $viewParams = [
                'user' => $user,
            ];
            return $this->view('XF:LostPassword\Index', 'forget_password_required_pgp', $viewParams);
        } else {

            return $this->view('XF:LostPassword\Index', 'forget_password_username_pgp');
        }
        return parent::actionIndex();
    }

    public function actionpgp(ParameterBag $params) {


        $message = $this->filter('message', 'str');

        $user = $this->assertRecordExists('XF:User', $params->user_id);

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);

        if(!$user->passphrase_3){
            
                $confirmationKey = $this->createConfirmation($user);

                $viewParams = [
                    'user' => $user,
                    'c' => $confirmationKey,
                    'bypgp' => 1,
                ];
                return $this->view('XF:LostPassword\Confirm', 'lost_password_confirm', $viewParams);
            
        }else{
            
             $viewParams = [
                    'user' => $user,
                  
                ];
                return $this->view('XF:LostPassword\Confirm', 'forget_verify_lass_pass', $viewParams);
            
        }
        
    }
    
    public function actionPass(ParameterBag $params){
        
       
        $passphrase_3 = $this->filter('passphrase_3', 'str');
      
        $user = $this->assertRecordExists('XF:User', $params->user_id);

        if (strcmp($user->passphrase_3, md5($passphrase_3)) !== 0) {

             throw new \XF\PrintableException(\XF::phrase('fs_invalid_passphrase'));

          
        }
       
        $confirmationKey = $this->createConfirmation($user);

        $viewParams = [
            'user' => $user,
            'c' => $confirmationKey,
            'bypgp' => 1,
        ];
        return $this->view('XF:LostPassword\Confirm', 'lost_password_confirm', $viewParams);
        
    
        
    }

    public function createConfirmation($user) {

        $userExit = $this->finder('XF:UserConfirmation')->where('user_id', $user->user_id)->fetchOne();

        if ($userExit) {

            $userExit->delete();
        }
        $userConfirmation = $this->em()->create('XF:UserConfirmation');

        $key = \XF::generateRandomString(16);
        $userConfirmation->confirmation_key = $key;
        $userConfirmation->confirmation_date = \XF::$time;
        $userConfirmation->confirmation_type = 'password';
        $userConfirmation->user_id = $user->user_id;
        $userConfirmation->save();

        return $key;
    }

    public function actionConfirm(ParameterBag $params) {


        $byPgp = $this->filter('bypgp', 'str');

        $user = $this->assertRecordExists('XF:User', $params->user_id);
        $lostPassword = $this->service('XF:User\PasswordReset', $user);

        $confirmationKey = $this->filter('c', 'str');

        if (!$lostPassword->isConfirmationVerified($confirmationKey)) {

            return $this->error(\XF::phrase('your_action_could_not_be_confirmed_request_new'));
        }

        if ($this->isPost() && $byPgp) {
            $passwords = $this->filter([
                'password' => 'str',
                'password_confirm' => 'str'
            ]);

            if (!$passwords['password']) {
                return $this->error(\XF::phrase('please_enter_valid_password'));
            }

            if (!$passwords['password_confirm'] || $passwords['password'] !== $passwords['password_confirm']) {
                return $this->error(\XF::phrase('passwords_did_not_match'));
            }

            $lostPassword->resetLostPassword($passwords['password']);

            if (!\XF::visitor()->user_id) {
                /** @var \XF\ControllerPlugin\Login $loginPlugin */
                $loginPlugin = $this->plugin('XF:Login');
                $loginPlugin->triggerIfTfaConfirmationRequired(
                        $user,
                        $this->buildLink('login/two-step', null, [
                            '_xfRedirect' => $this->buildLink('index')
                        ])
                );

               
            }

            return $this->redirect($this->buildLink('index'), \XF::phrase('your_password_has_been_reset'));
        }
        return parent::actionConfirm($params);
    }

}
