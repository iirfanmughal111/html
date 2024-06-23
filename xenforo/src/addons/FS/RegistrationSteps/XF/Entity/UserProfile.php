<?php

namespace FS\RegistrationSteps\XF\Entity;

use XF\Mvc\Entity\Structure;

class UserProfile extends XFCP_UserProfile
{
    public function getGroupTypeFields($type,$profile=0){

		$fieldDefinitions = $this->app()->container('customFields.users');
		$container = \xf::app()->container();
		
		$container['customFields.users'] = \xf::app()->fromRegistry('userFieldsInfo',
		   function(\XF\Container  $c) { return  $c['em']->getRepository('XF:UserField')->rebuildFieldCache(); },
		   function(array $userFieldsInfo) use($type,$profile)
		   {
						   
			   $class = 'XF\CustomField\DefinitionSet';
			   $class = \xf::app()->extendClass($class);

			   $definitionSet = new $class($userFieldsInfo);
			   $fields_ids = [];
			   
			if ($type == 'hobbyist_fields'){ 
				$fields_ids = explode(',',\XF::app()->options()->fs_register_hobbyiest_fields);
				
			}else if ($type == 'provider_fields'){
				$fields_ids = explode(',',\XF::app()->options()->fs_register_provider_fields);
				if($profile)
				{
				    
				   $fields_ids=array_merge($fields_ids,['HatedDiscussionTopics','specials','likedDiscussionTopics','PublicPhoneNumber','emailAdrress']);
				}
			
			}
	          
	        
	          
			   $definitionSet->addFilter('registration', function(array $field) use($fields_ids)
			   {
								 
					if(!in_array($field['field_id'],array_filter($fields_ids))){									   
						return false;
					}
                    else
                    {
                        return true;
                    }
		
			   });
			   
			  
			   return $definitionSet;
		   }
	   );
		   

			  
	//	var_dump($container['customFields.users']);exit;	   
	   $class = 'XF\CustomField\Set';
	   $class = $this->app()->extendClass($class);


	   return new $class($container['customFields.users'], $this);
	
	
	}
}