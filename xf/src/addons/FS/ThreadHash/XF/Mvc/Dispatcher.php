<?php

namespace FS\ThreadHash\XF\Mvc;

use XF\Http;
use XF\Mvc\Renderer\AbstractRenderer;
use XF\Mvc\Reply\AbstractReply;
use XF\PrintableException;

use function get_class, is_string, strlen;

class Dispatcher extends XFCP_Dispatcher
{


    public function run($routePath = null)
	{
		if ($routePath === null)
		{
			$routePath = $this->request->getRoutePath();
		}
		$tempurl = explode('.',$routePath);
		
		 if (isset($tempurl[1]) &&  substr($tempurl[0],0,8) == 'threads/'){
            $part2 = explode('/',$tempurl[1]);
			unset($part2[0]);
			$after= NULL; 
			foreach($part2 as $p){
				$after .= $p;
			} 
            //$after = (isset($part2[1]) && $part2[1] != '') ? $part2[1] : NULL; 
            //$after .= (isset($part2[2]) && $part2[2] != '') ? $part2[2] : NULL; 
			           
			$temproutePath = $tempurl[0].'.'.explode('-',$tempurl[1])[0].'/'.$after;
			$routePath = $temproutePath;
		}
        

		
		$match = $this->route($routePath);
		//var_dump($match);
		

		$earlyResponse = $this->beforeDispatch($match);
		if ($earlyResponse)
		{
			return $earlyResponse;
		}

		$reply = $this->dispatchLoop($match);

		$responseType = $reply->getResponseType() ? $reply->getResponseType() : $match->getResponseType();
		$response = $this->render($reply, $responseType);

		return $response;
	}

	
  


}