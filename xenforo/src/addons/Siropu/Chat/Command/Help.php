<?php

namespace Siropu\Chat\Command;

class Help
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          return $controller->plugin('Siropu\Chat:Chat')->help();
     }
}
