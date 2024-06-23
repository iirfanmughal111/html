<?php

namespace FS\RegistrationSteps\XF\Entity;

use XF\Mvc\Entity\Structure;

class Forum extends XFCP_Forum
{
	

    public function getCompaninionThreadIds(){
		$compainion_ids = explode(',',\XF::app()->options()->fs_register_compainion_ids);	
		return  in_array($this->node_id,$compainion_ids);
	}
	public function getReviewsThreadIds(){
		$reviews_ids = explode(',',\XF::app()->options()->fs_register_Reviews_ids);
		return  in_array($this->node_id,$reviews_ids);
	}

	
}