<?php

namespace FS\RegistrationSteps\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Register extends XFCP_Register {

    protected function getRegistrationInput(\XF\Service\User\RegisterForm $regForm) {

        $input = parent::getRegistrationInput($regForm);
        $input['account_type'] = $this->filter('account_type', 'int');
        return $input;
    }

    protected function finalizeRegistration(\XF\Entity\User $user) {
        $parent = parent::finalizeRegistration($user);
        
        $registration = $this->service('XF:User\Registration');
        if ($user && $user->email && $user->account_type == 2) {
            $registration->sendverifyMail($user);
        }
        $this->assingStaticGroup($user);
        return $parent;
    }
    protected function assingStaticGroup($user){
        if ($user &&  $user->account_type == 2) {
            $user->user_group_id = 5;
        }elseif ($user &&  $user->account_type == 1) {
            $user->user_group_id = 6;
        }
        $user->save();
    }

    protected function sendMail($user) {
        $mail = $this->app->mailer()->newMail()->setTo($user->email);
        $mail->setTemplate('fs_register_send_newAccount_mail', [
            'user' => $user]);
        $mail->send();
    }

    public function actionverify() {

        $verifyCode = $this->filter('i', 'str');

        if (!$verifyCode) {

            throw $this->exception(
                            $this->error(\XF::phrase("activation_key_not_found"))
            );
        }

        $user = $this->finder('XF:User')->where('activation_id', $verifyCode)->fetchOne();

        if (!$user) {

            throw $this->exception(
                            $this->error(\XF::phrase("invalid_activation_key"))
            );
        }

        $this->verifyAccountRequire($verifyCode, $user);

        return $this->redirect($this->getDynamicRedirect());
    }

   

    public function actiondirectVerify() {

        if ($this->ispost()) {

            $username = $this->filter('username', 'str');

            $verifyCode = $this->filter('activation_id', 'str');

            $user = $this->finder('XF:User')->where('username', $username)->fetchOne();

            if (!$user) {

                throw $this->exception(
                                $this->error(\XF::phrase("invalid_username"))
                );
            }


            if($user->is_verify){
                
                 throw $this->exception(
                                $this->error(\XF::phrase("you_have_already_verified"))
                );
                
            }
            if (!$verifyCode) {

                throw $this->exception(
                                $this->error(\XF::phrase("required_activation_key"))
                );
            }

            $this->verifyAccountRequire($verifyCode, $user);

            return $this->redirect($this->buildLink('forums'));
        }
        
        $visitor=\xf::visitor();

        if($visitor->user_id && $visitor->is_verify){
            
            throw $this->exception(
                                $this->error(\XF::phrase("you_have_already_verified"))
                );
            
        }

        return $this->view('FS\RegistrationSteps', 'fs_direct_verify_account');
    }

    public function verifyAccountRequire($verifyCode, $user) {

        if (strcmp($verifyCode, $user->activation_id) != 0) {

            throw new \XF\PrintableException(
                            $this->error(\XF::phrase("invalid_activation_key"))
            );
        }

        $user->user_state = "moderated";

        $user->is_verify = 1;

        $user->save();
        $this->sendMail($user);
    }
    
    public function actionverifyReagain(){
        
        $visitor=\xf::visitor();
        
        if(!$visitor->user_id || $visitor->is_verify){
            
           throw $this->exception($this->noPermission());   
            
        }
        
         $registration = $this->service('XF:User\Registration');
         $registration->sendverifyMail($visitor);
         
        return $this->redirect($this->getDynamicRedirect());
    }

   
        public function actionPremuim()
        {
            return $this->view('FS\RegistrationSteps', 'fs_register_premiuem');
        
        }
}