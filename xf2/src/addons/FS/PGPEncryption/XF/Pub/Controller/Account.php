<?php

namespace FS\PGPEncryption\XF\Pub\Controller;

class Account extends XFCP_Account {

//    protected function preferencesSaveProcess(\XF\Entity\User $visitor) {
//
//         return parent::preferencesSaveProcess($visitor);
//
//        $pgp = $this->filter('pgp_option', 'int');
//        $passphrase = $this->filter('passphrase_option', 'int');
//
//        $form = $this->formAction();
//
//        $input['user']['pgp_option'] = $pgp;
//        $input['user']['passphrase_option'] = $passphrase;
//        
//        if(!$visitor->public_key && $pgp){
//            
//            $link=$this->buildLink('account/security');
//   
//            throw new \XF\PrintableException(\XF::phrase('fs_for_enable_public_key',['link'=> $link]));
//        }
//
//        $form->basicEntitySave($visitor, $input['user'])->run();
//        return parent::preferencesSaveProcess($visitor);
//    }

    public function actionSecurity() {


        $input = $this->filter([
            'old_password' => 'str',
            'password' => 'str',
            'password_confirm' => 'str'
        ]);

        if ($this->isPost() && $input['old_password'] && $input['password']) {

            return parent::actionSecurity();
        }

        if ($this->isPost()) {



            $visitor = \xf::visitor();

            $pgp_option = $this->filter('pgp_option', 'int');
            $passphrase = $this->filter('passphrase_option', 'int');

            if (!$visitor->public_key) {

                $link = $this->buildLink('account/security');

                throw new \XF\PrintableException(\XF::phrase('fs_for_enable_public_key', ['link' => $link]));
            }

            if ($visitor->pgp_option != $pgp_option && $visitor->passphrase_option != $passphrase) {

                $this->updateUserMessage();
                $viewParams = [
                    'pgp_option' => $pgp_option,
                    'passphrase_option' => $passphrase,
                ];
                return $this->view('XF:Account\AccountDetails', 'both_option', $viewParams);
            }

            if ($visitor->passphrase_option != $passphrase) {


                $this->updateUserMessage();
                $viewParams = [
                    'passphrase_option' => $passphrase,
                ];
                return $this->view('XF:Account\AccountDetails', 'enable_passphrase_option', $viewParams);
            }
            if ($visitor->pgp_option != $pgp_option) {

                $this->updateUserMessage();
                $viewParams = [
                    'pgp_option' => $pgp_option,
                ];
                return $this->view('XF:Account\AccountDetails', 'enable_pgp_option', $viewParams);
            }

            return $this->redirect($this->buildLink('account/security'));
        }



        return parent::actionSecurity();
        ;
    }
    

    public function updateUserMessage() {

        $visitor = \xf::visitor();
        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
        list($encrypt_message, $random_message) = $PgpRegistration->encryptMessage($visitor->public_key);

        $visitor->fastUpdate('random_message', $random_message);
        $visitor->fastUpdate('encrypt_message', $encrypt_message);
    }

    public function actionchangepgp() {

        $message = $this->filter('message', 'str');

        $pgp_option = $this->filter('pgp_option', 'int');

        $user = \xf::visitor();

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);

        $user->fastUpdate('pgp_option', $pgp_option);

        return $this->redirect($this->buildLink('account/security'));
    }

    public function actionchangeboth() {

        $message = $this->filter('message', 'str');
        $pgp_option = $this->filter('pgp_option', 'int');
        $passphrase_option = $this->filter('passphrase_option', 'int');

        $user = \xf::visitor();

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);

        $user->fastUpdate('passphrase_option', $passphrase_option);

        $user->fastUpdate('pgp_option', $pgp_option);
        return $this->redirect($this->buildLink('account/security'));
    }

    public function actionchangePassphrase() {

        $message = $this->filter('message', 'str');

        $passphrase_option = $this->filter('passphrase_option', 'int');

        $user = \xf::visitor();

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);

        $user->fastUpdate('passphrase_option', $passphrase_option);

        return $this->redirect($this->buildLink('account/security'));
    }

    public function actionoldPublickey() {

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');

        if ($this->isPost()) {

            $message = $this->filter('message', 'str');

            $pgp_option = $this->filter('pgp_option', 'int');

            $user = \xf::visitor();

            $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
            $PgpRegistration->CheckEncryptMsg($user->random_message, $message);

            return $this->view('XF:Account\AccountDetails', 'new_public_key');
        }
        if (!$this->isPost()) {



            $user = \XF::visitor();

            if (!$user->public_key) {

                return $this->view('XF:Account\AccountDetails', 'new_public_key');
            }

            $publicKey = $this->filter('public_key', 'str');

            list($encrypt_message, $message) = $PgpRegistration->encryptMessage($user->public_key);

            $user->fastUpdate('random_message', $message);

            $viewParams = [
                'encrypt_message' => $encrypt_message,
            ];

            return $this->view('XF:Account\AccountDetails', 'require_encrypt_message_for_old_key', $viewParams);
        }
    }

    public function actionnewPublicKey() {

        $user = \XF::visitor();
        $publicKey = $this->filter('public_key', 'str');

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
        list($encrypt_message, $message) = $PgpRegistration->encryptMessage($publicKey);

        $user->fastUpdate('random_message', $message);

        $viewParams = [
            'encrypt_message' => $encrypt_message,
            'public_key' => $publicKey
        ];
        return $this->view('XF:Account\AccountDetails', 'require_encrypt_message_for_new_key', $viewParams);
    }

    public function actionSavePublickey() {



        $message = $this->filter('message', 'str');

        $encrypt_message = $this->filter('encrypt_message', 'str');
        $publicKey = $this->filter('public_key', 'str');
        $user = \xf::visitor();

        $PgpRegistration = $this->service('FS\PGPEncryption:PGPMessage');
        $PgpRegistration->CheckEncryptMsg($user->random_message, $message);

        $user->fastUpdate('encrypt_message', $encrypt_message);
        $user->fastUpdate('public_key', $publicKey ?: $user->public_key);
        return $this->redirect($this->buildLink('account/security'));
    }

}
