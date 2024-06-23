<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class UserAlert extends Repository
{
     public function getUnreadContentAlertCount($alertedUserId, $contentType, $contentId)
     {
		return $this->finder('XF:UserAlert')
               ->where('alerted_user_id', $alertedUserId)
               ->where('content_type', $contentType)
               ->where('content_id', $contentId)
               ->where('view_date', 0)
               ->fetch()
               ->count();
     }
}
