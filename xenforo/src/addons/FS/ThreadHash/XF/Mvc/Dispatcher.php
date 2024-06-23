<?php

namespace FS\ThreadHash\XF\Mvc;

use XF\Http;
use XF\Mvc\Renderer\AbstractRenderer;
use XF\Mvc\Reply\AbstractReply;
use XF\PrintableException;
use function get_class,
             is_string,
             strlen;

class Dispatcher extends XFCP_Dispatcher {

    public function run($routePath = null) {


//        $parent=parent::run($routePath);
        if ($routePath === null) {
            $routePath = $this->request->getRoutePath();
        }
       
        
        $match = $this->getRouter()->routeToController($routePath, $this->request);

        
        if(strpos($routePath,'@')!=false && $match->getController()=="XF:Thread"){
            
                $tempurl = explode('@', $routePath);

           
                if (count($tempurl)) {

                     $pageNo = explode('/', $tempurl[1]);

                     if(count($pageNo)){

                          $threadurl = $tempurl[0]."/".end($pageNo);
                          
                     }else{

                         $threadurl = $tempurl[0];
                     }
                    $match = $this->route($threadurl);

                    $earlyResponse = $this->beforeDispatch($match);
                    if ($earlyResponse) {
                        return $earlyResponse;
                    }

                    $reply = $this->dispatchLoop($match);

                    $responseType = $reply->getResponseType() ? $reply->getResponseType() : $match->getResponseType();
                    $response = $this->render($reply, $responseType);

                    return $response;
                }
        
        }


        return parent::run($routePath);
    }
}
