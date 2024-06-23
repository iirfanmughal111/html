<?php

namespace FS\PGPEncryption\XF\Pub\Controller;

class Register extends XFCP_Register {

    public function actionIndex() {


        $this->app()->db()->delete('xf_user', 'verify_pgp = ?', 0);

        return parent::actionIndex();
    }

    public function actionRegister() {

        $this->assertPostOnly();
        $this->assertRegistrationActive();

        /** @var \XF\Service\User\RegisterForm $regForm */
        $regForm = $this->service('XF:User\RegisterForm', $this->session());
        if (!$regForm->isValidRegistrationAttempt($this->request(), $error)) {
            // they failed something that a legit user shouldn't fail, redirect so the key is different
            $regForm->clearStateFromSession($this->session());
            return $this->redirect($this->buildLink('register'));
        }

        $privacyPolicyUrl = $this->app->container('privacyPolicyUrl');
        $tosUrl = $this->app->container('tosUrl');

        if (($privacyPolicyUrl || $tosUrl) && !$this->filter('accept', 'bool')) {
            if ($privacyPolicyUrl && $tosUrl) {
                return $this->error(\XF::phrase('please_read_and_accept_our_terms_and_privacy_policy_before_continuing'));
            } else if ($tosUrl) {
                return $this->error(\XF::phrase('please_read_and_accept_our_terms_and_rules_before_continuing'));
            } else {
                return $this->error(\XF::phrase('please_read_and_accept_our_privacy_policy_before_continuing'));
            }
        }

        if (!$this->captchaIsValid()) {
            return $this->error(\XF::phrase('did_not_complete_the_captcha_verification_properly'));
        }

        $input = $this->getRegistrationInput($regForm);
        $registration = $this->setupRegistration($input);
        $registration->checkForSpam();

        if (!$registration->validate($errors)) {
            return $this->error($errors);
        }

        $user = $registration->save();
        $user->fastUpdate('verify_pgp', 0);

//        exit;
        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

        list($encrypt_message, $message) = $PgpRegistration->encryptMessage($user->public_key);

        $user->fastUpdate('random_message', $message);
        $user->fastUpdate('encrypt_message', $encrypt_message);

        $viewParams = [
            'user' => $user
        ];

        return $this->view('XF:Register\Form', 'pgp_register', $viewParams);
    }

    protected function setupRegistration(array $input) {

         $publicKey = $this->filter('public_key', 'str');
        $passphrase_1 = $this->filter('passphrase_1', 'str');
        $passphrase_2 = $this->filter('passphrase_2', 'str');
        $passphrase_3 = $this->filter('passphrase_3', 'str');


        
        if ($passphrase_1 == $passphrase_2 || $passphrase_1 == $passphrase_3) {

            throw new \XF\PrintableException(\XF::phrase('fs_passphrase_should_not_be_similar'));
            
        }elseif($passphrase_2 == $passphrase_3 || $passphrase_2 == $passphrase_1){
            
            throw new \XF\PrintableException(\XF::phrase('fs_passphrase_should_not_be_similar'));
        }
        elseif($passphrase_3 == $passphrase_1 || $passphrase_3 == $passphrase_2){
            
            throw new \XF\PrintableException(\XF::phrase('fs_passphrase_should_not_be_similar'));
        }
        $passphraseLength = \xf::options()->fs_passphrase_length;

        if (strlen($passphrase_1) < $passphraseLength || strlen($passphrase_2) < $passphraseLength || strlen($passphrase_3) < $passphraseLength) {

            throw new \XF\PrintableException(\XF::phrase('fs_minimum_passphrase_should_be', ['length' => $passphraseLength]));
        }

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

        list($encrypt_message, $message) = $PgpRegistration->encryptMessage($publicKey);

        if ($publicKey) {

            $input['public_key'] = $publicKey;
            $input['encrypt_message'] = $encrypt_message;
            $input['random_message'] = $message;
            $input['passphrase_1'] = md5($passphrase_1);
            $input['passphrase_2'] = md5($passphrase_2);
            $input['passphrase_3'] = md5($passphrase_3);
        }
        
    

        return parent::setupRegistration($input);
    }

    public function actionfindUsername() {

        $username = ltrim($this->filter('username', 'str', ['no-trim']));

        $User = $this->finder('XF:User')->where('username', $username)->fetchOne();

        if (isset($User->encrypt_message) && $User->encrypt_message) {

            $resp = 'true';
            $encrypt_message = $User->encrypt_message;
        } else {
            $resp = 'false';
            $encrypt_message = '';
        }

        $viewParams = [
            'response' => $resp,
            'encrypt_message' => $encrypt_message,
        ];

        return $this->view('FS\PGPEncryption\XF:Item\check', '', $viewParams);
    }

    public function actionverifyMessage() {



        $userId = $this->filter('user_id', 'int');

        $user = $this->finder('XF:User')->where('user_id', $userId)->fetchOne();

        $message = $this->filter('message', 'str');

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);

        $user->fastUpdate('verify_pgp', 1);

        $this->finalizeRegistration($user);

        return $this->redirect($this->buildLink('register/complete'));
    }

    public function actionloginPass() {

        $user = \xf::visitor();
        $publicKey = $this->filter('public_key', 'str');
        $passphrase_1 = $this->filter('passphrase_1', 'str');
        $passphrase_2 = $this->filter('passphrase_2', 'str');

        if ($passphrase_1 == $passphrase_2) {

            throw new \XF\PrintableException(\XF::phrase('fs_passphrase_should_not_be_similar'));
        }

        $passphraseLength = \xf::options()->fs_passphrase_length;

        if (strlen($passphrase_1) < $passphraseLength || strlen($passphrase_2) < $passphraseLength) {

            throw new \XF\PrintableException(\XF::phrase('fs_minimum_passphrase_should_be', ['length' => $passphraseLength]));
        }
        
        


        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

        list($encrypt_message, $message) = $PgpRegistration->encryptMessage($publicKey);

        
        $user->fastUpdate('random_message', $message);
        $user->fastUpdate('encrypt_message', $encrypt_message);

        $viewParams = [
            'user' => $user,
            'publicKey' => $publicKey,
            'passphrase_1'    =>$passphrase_1,
            'passphrase_2'    =>$passphrase_2,
        ];

        
        return $this->view('XF:Account\AccountDetails', 'login_pgp_pass', $viewParams);
  
    }
    
    public function actionloginPassLast(){
        
        /* in version 2.6 add the third passphrase for forget password*/
        $user = \xf::visitor();
        $publicKey = $this->filter('public_key', 'str');
        $passphrase_3 = $this->filter('passphrase_3', 'str');
       

        $passphraseLength = \xf::options()->fs_passphrase_length;

        if (strlen($passphrase_3) < $passphraseLength) {

            throw new \XF\PrintableException(\XF::phrase('fs_minimum_passphrase_should_be', ['length' => $passphraseLength]));
        }
        
        


        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

        list($encrypt_message, $message) = $PgpRegistration->encryptMessage($publicKey);

        
        $user->fastUpdate('random_message', $message);
        $user->fastUpdate('encrypt_message', $encrypt_message);

        $viewParams = [
            'user' => $user,
            'publicKey' => $publicKey,
            'passphrase_3'    =>$passphrase_3,
       
        ];

    
        return $this->view('XF:Account\AccountDetails', 'login_pgp_pass_last', $viewParams);
  
        
    }
    
    public function actionpgpverifylast(){
        
        
        
        $message = $this->filter('message', 'str');
     
        $publicKey = $this->filter('publicKey', 'str');

        $passphrase_3 = $this->filter('passphrase_3', 'str');
      
        $user = \xf::visitor();

       
        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);
        
        $user->fastUpdate('passphrase_3', md5($passphrase_3));

        $user->fastUpdate('passphrase_option', 1);
        
        $user->fastUpdate('public_key',$publicKey);
        $user->fastUpdate('pgp_option',1);
        
     
        $redirect = $this->getDynamicRedirect(null, false);
        
        return $this->redirect($redirect);
        
    }
    
    public function actionpgpVerify(){
       
        
        $message = $this->filter('message', 'str');
        
     
        $publicKey = $this->filter('publicKey', 'str');
        $passphrase_1 = $this->filter('passphrase_1', 'str');
        $passphrase_2 = $this->filter('passphrase_2', 'str');

  
      
        $user = \xf::visitor();

       
        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);
        
        $user->fastUpdate('passphrase_1', md5($passphrase_1));

        $user->fastUpdate('passphrase_2', md5($passphrase_2));

        $user->fastUpdate('passphrase_option', 1);
        
        $user->fastUpdate('public_key',$publicKey);
        $user->fastUpdate('pgp_option',1);
        
        $redirect = $this->getDynamicRedirect(null, false);
        
        return $this->redirect($redirect);
    }

}
