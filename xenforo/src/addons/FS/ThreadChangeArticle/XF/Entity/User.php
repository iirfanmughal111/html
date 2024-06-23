<?php

namespace FS\ThreadChangeArticle\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    
    public function canChangeThreadStyle(){
        
        return $this->hasPermission('xc_thread_article', 'xc_change_to_article_view');
        
    }
}
