<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class UserCommand extends Repository
{
     public function findUserCommands($userId)
     {
          return $this->finder('Siropu\Chat:UserCommand')
               ->where('command_user_id', $userId);
     }
     public function findUserCommand($userId, $command)
     {
          return $this->finder('Siropu\Chat:UserCommand')
               ->where('command_user_id', $userId)
               ->where('command_name', $command)
               ->fetchOne();
     }
}
