<?php

namespace Siropu\Chat\Command;

class Me
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          $messageEntity->message_type = 'me';
          $messageEntity->message_text = $input;
     }
}
