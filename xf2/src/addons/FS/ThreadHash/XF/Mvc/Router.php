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
            $part2 = isset($url[1]) ? explode('/',$url[1])[1] : null;  
            $id = explode('/',$url[1])[0];
            $thread = \XF::app()->finder('XF:Thread')->where('thread_id',$id)->fetchOne();
            
            if ($thread){
                $finalUrl =   $part1.'?'.$url[0].'.'.$id.'-'.$thread->thread_hash.'/'.$part2; 
            } else {
                $finalUrl =   $part1.'?'.$url[0].'.'.$id.'-hash'.time().$part2; 
            }
                
        }
            
		if ($hash)
		{
			$finalUrl .= '#' . ltrim($hash, '#');
		}
     
        
		return $finalUrl;
	}
    
}