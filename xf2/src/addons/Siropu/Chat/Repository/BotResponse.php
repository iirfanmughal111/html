<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class BotResponse extends Repository
{
     public function findBotResponsesForList()
     {
          return $this->finder('Siropu\Chat:BotResponse');
     }
     public function getBotResponseEnabledCount()
     {
          return $this->findBotResponsesForList()->isEnabled()->total();
     }
     public function rebuildBotResponseCache()
     {
          $this->repository('XF:Option')->updateOption('siropuChatActiveBotResponseCount', $this->getBotResponseEnabledCount());
     }
}
