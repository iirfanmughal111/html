<?php

namespace Siropu\Chat\Util;

class Action
{
     public static function writeData(array $data = [])
     {
          $options = \XF::options();

          if ($options->siropuChatActionJsonFileCache)
          {
               try
               {
                    \XF\Util\File::writeToAbstractedPath('data://siropu/chat/action/data.json', json_encode($data));
               }
               catch (\Exception $e)
               {
                    \XF::logException($e, false, 'Error writing Chat action JSON file');
               }
          }
          else
          {
               $simpleCache = \XF::app()->simpleCache();
               $simpleCache['Siropu/Chat']['actions'] = $data;
          }
     }
     public static function getData($checkPermissions = false)
     {
          $options = \XF::options();

          if ($options->siropuChatActionJsonFileCache)
          {
               $data = @json_decode(file_get_contents('data/siropu/chat/action/data.json'), true) ?: [];
          }
          else
          {
               $simpleCache = \XF::app()->simpleCache();
               $data = $simpleCache['Siropu/Chat']['actions'] ?: [];
          }

          if ($checkPermissions)
          {
               $visitor = \XF::visitor();

               if (!empty($data['rooms']))
               {
                    foreach ($data['rooms'] as $itemId => $actions)
                    {
                         if (!$visitor->hasJoinedRoomSiropuChat($itemId))
                         {
                              unset($data['rooms'][$itemId]);
                         }
                    }
               }

               if (!empty($data['conversations']))
               {
                    foreach ($data['conversations'] as $itemId => $actions)
                    {
                         if (!$visitor->hasConversationSiropuChat($itemId))
                         {
                              unset($data['conversations'][$itemId]);
                         }
                    }
               }
          }

          return $data;
     }
}
