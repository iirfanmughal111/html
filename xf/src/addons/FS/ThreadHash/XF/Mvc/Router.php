<?php

namespace FS\ThreadHash\XF\Mvc;

use XF\Http;
use XF\Mvc\Renderer\AbstractRenderer;
use XF\Mvc\Reply\AbstractReply;
use XF\PrintableException;

use function get_class, is_string, strlen;

class Router extends XFCP_Router
{

    public function buildLink($link, $data = null, array $parameters = [], $hash = null)
	{
		if (is_array($link))
		{
			$tempLink = $link;
			$link = $tempLink[0];
			if (!$parameters)
			{
				$parameters = $tempLink[1];
			}
		}

		$parts = explode(':', $link);
		if (isset($parts[1]))
		{
			$modifier = $parts[0];
			$link = $parts[1];
		}
		else
		{
			$modifier = null;
		}

		if ($hash instanceof \Closure)
		{
			// this happens before the actual link building as we may manipulate parameters there in a way that may
			// lose something like the page number
			$hash = $hash($link, $data, $parameters);
		}

		$finalUrl = $this->buildFinalUrl(
			$modifier,
			$this->buildLinkPath($link, $data, $parameters),
			$parameters
		);
        
        
        
        $tempUrl = explode('?',$finalUrl);
        if (isset($tempUrl[1]) && 'threads/' == substr($tempUrl[1],0,8)){
            $part1 = explode('?',$finalUrl)[0];
            $url = explode('.',$tempUrl[1]);
            //$part2 = isset($url[1]) ? explode('/',$url[1])[1] : NULL; 
		   $part2 = isset($url[1]) ? explode('/',$url[1]) : null; 
			$after= NULL; 
			if ($part2){
				unset($part2[0]);
				foreach($part2 as $p){
					$after .= $p.'/';
				}
				$after = substr($after, 0, -1);
			}
			
            //$tempPart = isset($url[1]) ? explode('/',$url[1]) : null; 
           // $part3 = isset($tempPart[2]) ? ('/'.$tempPart[2]) : null;
           // $part2 .=$part3;
           
            $id = explode('/',$url[1])[0];
            $thread = \XF::app()->finder('XF:Thread')->where('thread_id',$id)->fetchOne();
        
            if ($thread){
                $finalUrl =   $part1.'?'.$url[0].'.'.$id.'-'.$thread->thread_hash.'/'.$after; 
            } else {
                $finalUrl =   $part1.'?'.$url[0].'.'.$id.'-hash'.time().$after; 
            }
                
        }
            
		if ($hash)
		{
			$finalUrl .= '#' . ltrim($hash, '#');
		}
		return $finalUrl;
	}
    
}