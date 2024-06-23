<?php

namespace FS\EncryptIp\XF\Entity;

use XF\Mvc\Entity\Structure;

class IpMatch extends XFCP_IpMatch
{
    // public function getip()
	// {
    //     $ip = $this->ip_;
	// 	$route = \XF::app()->request()->getRoutePath();
	// 	//  var_dump($route);exit;
	// 	if (( $route == 'banning/ips/add' && !\XF::app()->request()->isPost()) || $route == 'banning/discouraged-ips/add' && \XF::app()->request()->isPost()) {
	// 		$ip = $this->ip_;
	// 	}
	// 	else if($route == 'banning/ips/add' && \XF::app()->request()->isPost()){
	// 		$ip = $this->ip_; //extra
	// 	}
        
	// 	return $ip;
	
	// }

	// public function setip()
	// {
	// 	$this->ip = 'vzfRY3sYkbDC8s82';
	// 	var_dump('reached');
	// 	exit;
	// }
    
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure); 
        $structure->columns['first_byte'] =  ['type' => self::STR ];
        $structure->columns['start_range'] =  ['type' => self::STR ];
        $structure->columns['end_range'] =  ['type' => self::STR ];
		$structure->columns['first_byte_backup'] =  ['type' => self::STR ];
        $structure->columns['start_range_backup'] =  ['type' => self::STR ];
        $structure->columns['end_range_backup'] =  ['type' => self::STR ];
        // $structure->getters = [
		// 	'ip' => ['getter' => 'getip'],
		// ];
        return $structure;
    }


}