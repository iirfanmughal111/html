<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Command extends Repository
{
     public function findCommandsForList()
     {
          return $this->finder('Siropu\Chat:Command')->order('command_name', 'ASC');
     }
     public function findActiveCommands()
     {
          return $this->findCommandsForList()->enabled();
     }
     public function findActiveCommand($command)
     {
          return $this->finder('Siropu\Chat:Command')->where('command_name', $command)->enabled()->fetchOne();
     }
     public function findCommandByDefaultName($command)
     {
          return $this->finder('Siropu\Chat:Command')->where('command_name_default', $command)->fetchOne();
     }
     public function getCommandCacheData()
     {
          $cache = [];

          foreach ($this->findActiveCommands()->fetch() AS $command)
          {
               $values = $command->toArray();

               $values['command_user_groups'] = json_encode($command->command_user_groups);
               $values['command_rooms']       = json_encode($command->command_rooms);
               $values['command_options']     = json_encode($command->command_options);

               $cache[$command->command_name] = $values;
          }

          return $cache;
     }
     public function getDefaultCommandCacheData()
     {
          $cache = [];

          foreach ($this->findActiveCommands()->fetch() AS $command)
          {
               $cache[$command->command_name_default] = $command->command_name;
          }

          return $cache;
     }
     public function rebuildCommandCache()
     {
          $simpleCache = $this->app()->simpleCache();
          $simpleCache['Siropu/Chat']['commands'] = $this->getCommandCacheData();
          $simpleCache['Siropu/Chat']['commandsDefault'] = $this->getDefaultCommandCacheData();
     }
     public function getCommandFromCache($commandName)
     {
          $cache = $this->getCommandCache();

          if (isset($cache[$commandName]))
          {
               return $this->instantiateCommandEntity($cache[$commandName]);
          }
     }
     public function getDefaultCommandFromCache($commandName)
     {
          $cache = $this->getDefaultCommandCache();

          if (isset($cache[$commandName]))
          {
               return $cache[$commandName];
          }
     }
     public function instantiateCommandEntity(array $command)
     {
          return $this->em->instantiateEntity('Siropu\Chat:Command', $command);
     }
     public function getCommandCache()
     {
          $simpleCache = $this->app()->simpleCache();
          return $simpleCache['Siropu/Chat']['commands'];
     }
     public function getDefaultCommandCache()
     {
          $simpleCache = $this->app()->simpleCache();
          return $simpleCache['Siropu/Chat']['commandsDefault'];
     }
}
