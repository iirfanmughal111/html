<?php

namespace FS\HideUsernames\XF\Str;

use XF\Template\Templater;

class Formatter extends  XFCP_Formatter
{
    
    public function stripBbCode($string, array $options = []) {
        
         $parent=parent::stripBbCode($string, $options);
      
               if (stripos(strtolower($parent), '@') !== false){
                   
                  
                      $options = array_merge([
			'stripQuote' => false,
			'hideUnviewable' => true
		], $options);

		if ($options['stripQuote'])
		{
			$parts = preg_split('#(\[quote[^\]]*\]|\[/quote\])#i', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			$string = '';
			$quoteLevel = 0;
			foreach ($parts AS $i => $part)
			{
				if ($i % 2 == 0)
				{
					// always text, only include if not inside quotes
					if ($quoteLevel == 0)
					{
						$string .= rtrim($part) . "\n";
					}
				}
				else
				{
					// quote start/end
					if ($part[1] == '/')
					{
						// close tag, down a level if open
						if ($quoteLevel)
						{
							$quoteLevel--;
						}
					}
					else
					{
						// up a level
						$quoteLevel++;
					}
				}
			}
		}

		// replaces unviewable tags with a text representation
		$string = str_replace('[*]', '', $string);
		$string = preg_replace(
			'#\[(attach|media|img|spoiler|ispoiler)[^\]]*\].*\[/\\1\]#siU',
			$options['hideUnviewable'] ? '' : '[\\1]',
			$string
		);

		// split the string into possible delimiters and text; even keys (from 0) are strings, odd are delimiters
		$parts = preg_split('#(\[\w+(?:=[^\]]*)?+\]|\[\w+(?:\s?\w+="[^"]*")+\]|\[/\w+\])#si', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
		$total = count($parts);
		if ($total < 2)
		{
			return trim($string);
		}

//                var_dump($parts);
		$closes = [];
		$skips = [];
		$newString = '';

		// first pass: find all the closing tags and note their keys
		for ($i = 1; $i < $total; $i += 2)
		{
			if (preg_match("#^\\[/(\w+)]#i", $parts[$i], $match))
			{
                          
				$closes[strtolower($match[1])][$i] = $i;
			}
		}

		// second pass: look for all the text elements and any opens, then find
		// the first corresponding close that comes after it and remove it.
		// if we find that, don't display the open or that close
                
               
		for ($i = 0; $i < $total; $i++)
		{
			$part = $parts[$i];
                        
                        
			if ($i % 2 == 0)
			{
                            $id=$i-1;
                            
                          if (isset($parts[$id]) && stripos(strtolower($parts[$id]), '[USER=') !== false){
                                
                               
                                $userId = (int) filter_var($parts[$id], FILTER_SANITIZE_NUMBER_INT);
                                
                                if($userId){
                                    
                                    $user=\xf::app()->finder('XF:User')->where('user_id',$userId)->fetchOne();
                                    if($user){
                                        
                                        $newString.= "@".$user->username;
                                        continue;
                                    }
                                }
                                
                               
                            }
//                           
				$newString .= $part;
				continue;
			}

			if (!empty($skips[$i]))
			{
				// known close
				continue;
			}
//                        var_dump($newString);

			if (preg_match('/^\[(\w+)(?:=|\s?\w+="[^"]*"|\])/i', $part, $match))
			{
                           
                           
                            
				$tagName = strtolower($match[1]);
                           
//                                 var_dump($tagName,$part,$match);
                                
				if (!empty($closes[$tagName]))
				{
                                   
					do
					{
                                            
						$closeKey = reset($closes[$tagName]);
                                              
                                              
						if ($closeKey)
						{
							unset($closes[$tagName][$closeKey]);
						}
					}
					while ($closeKey && $closeKey < $i);
					if ($closeKey)
					{
						// found a matching close after this tag
						$skips[$closeKey] = true;
						continue;
					}
				}
                                
                                
			}
                        
                        
                        
                    

			$newString .= $part;
                        

		}

		return trim($newString);
                         
                        
               }

   
        
        return $parent;
    }
    
   
    
    
    
}