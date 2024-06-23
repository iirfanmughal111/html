<?php

namespace FS\HideUsernames\XF\BbCode\Renderer;

use XF\BbCode\Traverser;
use XF\Str\Formatter;
use XF\Template\Templater;
use XF\Util\Arr;

class Html extends XFCP_Html
{
    
    protected function getRenderedUser($content, int $userId) {
        
        $user=\xf::app()->finder('XF:User')->where('user_id',$userId)->fetchOne();
        
        if($user){
            
            $link = \XF::app()->router('public')->buildLink('full:members', ['user_id' => $userId]);

		return $this->wrapHtml(
			'<a href="' . htmlspecialchars($link) . '" class="username" data-xf-init="member-tooltip" data-user-id="' . $userId .  '" data-username="' . $content . '">@',
			$user->username,
			'</a>'
		);
        }
       
        return parent::getRenderedUser($content, $userId);
    }
    
    
}