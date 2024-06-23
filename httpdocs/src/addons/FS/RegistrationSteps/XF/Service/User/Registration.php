<?php

namespace FS\RegistrationSteps\XF\Service\User;

class Registration extends XFCP_Registration {

    public function setFromInput(array $input) {
        $parent = parent::setFromInput($input);
        if (isset($input['account_type'])) {
            $this->setAccountType($input['account_type']);
        }

        return $parent;
    }

    public function setAccountType($type) {
        $this->user->account_type = $type;
    }

    public function setCustomFields(array $values) {
        /** @var \XF\CustomField\Set $fieldSet */
        $account_type = \xf::app()->request()->filter('account_type', 'uint');

        if ($account_type && $account_type == 2) {
            $fieldSet = $this->user->Profile->getGroupTypeFields('provider_fields');
            $this->setCustomFieldAccountType($fieldSet, $values);
            return;
        } elseif ($account_type && $account_type == 1) {
            $fieldSet = $this->user->Profile->getGroupTypeFields('hobbyist_fields');
            $this->setCustomFieldAccountType($fieldSet, $values);
            return;
        }

        return parent::setCustomFields($values);
    }

    public function setCustomFieldAccountType($fieldSet, $values) {

        $fieldDefinition = $fieldSet->getDefinitionSet()
                ->filterEditable($fieldSet, 'user')
                ->filter('registration');

        $customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

        if ($customFieldsShown) {
            $fieldSet->bulkSet($values, $customFieldsShown);
        }
    }

    protected function setInitialUserState() {
        if ($this->user->account_type == 2 && intval($this->app->options()->Advertiser_manaul_approval == 1)) {
            $this->user->user_state = 'moderated';
            return;
        } else {
            return parent::setInitialUserState();
        }
    }

    public function sendverifyMail($user) {

        $activationId = $this->generateVerifId();

        $mail = \xf::app()->mailer()->newMail()->setTo($user->email);

        $mail->setTemplate('fs_verify_account', [
            'user' => $user,
            'link' => $this->registerVerifyUrl($activationId),
            'direct_link' => $this->registerDirectVerifyUrl(),
            'activation_id' => $activationId
        ]);

        $mail->send();

        $this->changeState($user, $activationId);
    }

    public function changeState($user, $activationId) {

        $user->is_verify = 0;
        $user->activation_id = $activationId;
        $user->save();
    }

    public function registerDirectVerifyUrl() {
        return \XF::app()->router('public')->buildLink('canonical:register/direct-verify');
    }

    public function registerVerifyUrl($activationId) {
        return \XF::app()->router('public')->buildLink('canonical:register/verify', null, array('i' => $activationId));
    }

    public function generateVerifId() {

        return md5(\XF::generateRandomString(30));
    }
}
