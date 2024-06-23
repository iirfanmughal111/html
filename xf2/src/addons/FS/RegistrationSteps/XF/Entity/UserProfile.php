<?php

namespace FS\RegistrationSteps\XF\Entity;

use XF\Mvc\Entity\Structure;

class UserProfile extends XFCP_UserProfile
{
    public function getGroupTypeFields($type){

		$fieldDefinitions = $this->app()->container('customFields.users');
		$container = \xf::app()->container();
		
		$container['customFields.users'] = \xf::app()->fromRegistry('userFieldsInfo',
		   function(\XF\Container  $c) { return  $c['em']->getRepository('XF:UserField')->rebuildFieldCache(); },
		   function(array $userFieldsInfo) use($type)
		   {
						   
			   $class = 'XF\CustomField\DefinitionSet';
			   $class = \xf::app()->extendClass($class);

			   $definitionSet = new $class($userFieldsInfo);
			   $fields_ids = [];
			   
			if ($type == 'hobbyist_fields'){ 
				$fields_ids = explode(',',\XF::app()->options()->fs_register_hobbyiest_fields);
			}else if ($type == 'provider_fields'){
				$fields_ids = explode(',',\XF::app()->options()->fs_register_provider_fields);
			}
			   $definitionSet->addFilter('registration', function(array $field) use($fields_ids)
			   {
								 
					if(!in_array($field['field_id'],array_filter($fields_ids))){									   
						return false;
					}

				   return (!empty($field['show_registration']) || !empty($field['required']));
			   });
			   
			   return $definitionSet;
		   }
	   );
		   

			   
			   
	   $class = 'XF\CustomField\Set';
	   $class = $this->app()->extendClass($class);


	   return new $class($container['customFields.users'], $this);
	
	
	}
}