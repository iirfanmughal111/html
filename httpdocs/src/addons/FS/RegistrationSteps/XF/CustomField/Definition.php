<?php

namespace FS\RegistrationSteps\XF\CustomField;


class Definition extends XFCP_Definition
{
	public function getExsitedField(){
		$fields_ids_1 = explode(',',\XF::app()->options()->fs_register_hobbyiest_fields);
		$fields_ids_2 = explode(',',\XF::app()->options()->fs_register_provider_fields);
		$fields_ids  = array_filter(array_merge($fields_ids_1,$fields_ids_2));	
		return  in_array($this->field['field_id'],$fields_ids) ? false : true;
	}

	public function getAccountTypeField(){
		if (\XF::visitor()->account_type==2){
			$fields_ids = explode(',',\XF::app()->options()->fs_register_provider_fields);
			
		}else{
			$fields_ids = explode(',',\XF::app()->options()->fs_register_hobbyiest_fields);
		}
		return  in_array($this->field['field_id'],$fields_ids) ? false : true;
		
	}

}