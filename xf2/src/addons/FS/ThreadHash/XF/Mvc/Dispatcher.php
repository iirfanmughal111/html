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
            
                $after = (isset($part2[1]) && $part2[1] != '') ? $part2[1] : NULL; 
        
            
			$temproutePath = $tempurl[0].'.'.explode('-',$tempurl[1])[0].'/'.$after;
			$routePath = $temproutePath;
		}
        
        //var_dump($routePath,$temproutePath);
		
		$match = $this->route($routePath);
		

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

	public function route($routePath)
	{

        return parent::route($routePath);

        var_dump("Asdf");exit;
    }
  


}