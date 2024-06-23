<?php

namespace FS\RegistrationSteps\XF\Pub\Controller;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\ParameterBag;
use XF\Mvc\FormAction;
use XF\Mvc\Reply\View;

use function boolval, count, is_array, strlen;

class Account extends XFCP_Account
{
    protected function customFieldsSaveProcess(FormAction $form, $group, \XF\Entity\UserProfile $userProfile = null, $entitySave = false)
	{
      
		if ($userProfile === null)
		{
			$userProfile = \XF::visitor()->getRelationOrDefault('Profile');
		}

		/** @var \XF\CustomField\Set $fieldSet */
        
        $account_type  = \XF::visitor()->account_type;
        if ($account_type && $account_type==2){
			$fieldSet = $userProfile->getGroupTypeFields('provider_fields');
		}elseif($account_type && $account_type==1){
			$fieldSet = $userProfile->getGroupTypeFields('hobbyist_fields');
		}
		
        if ($account_type && ($account_type==1 || $account_type==2)){
			$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterGroup($group)
			->filterEditable($fieldSet, 'user')->filter('registration');
			$this->setCustomFieldAccountType($fieldSet,$fieldDefinition,$entitySave,$userProfile,$form);
			return;
        }
		return parent::customFieldsSaveProcess($form,$group,$userProfile,$entitySave);
		

		
	}

	public function setCustomFieldAccountType($fieldSet,$fieldDefinition,$entitySave,$userProfile,$form){
		$customFields = $this->filter('custom_fields', 'array');
		$customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

		if ($customFieldsShown)
		{
			$form->setup(function() use ($fieldSet, $customFields, $customFieldsShown)
			{
				$fieldSet->bulkSet($customFields, $customFieldsShown);
			});
		}

		if ($entitySave)
		{
			$form->validateEntity($userProfile)->saveEntity($userProfile);
		}
	}

}