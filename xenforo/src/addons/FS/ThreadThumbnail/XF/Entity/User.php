<?php

namespace FS\ThreadThumbnail\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    
    public function canChangeThreadThumbnail(){
        return $this->hasPermission('fs_thread_thumbnail_group', 'fs_thread_thumbnail');
        
    }
}