<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class BotMessage extends Repository
{
     public function findBotMessagesForList()
     {
          return $this->finder('Siropu\Chat:BotMessage');
     }
     public function getBotMessageEnabledCount()
     {
          return $this->findBotMessagesForList()->isEnabled()->total();
     }
     public function rebuildBotMessageCache()
     {
          $this->repository('XF:Option')->updateOption('siropuChatActiveBotMessageCount', $this->getBotMessageEnabledCount());
     }
}
