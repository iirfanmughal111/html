<?php

namespace Siropu\Chat\Navigation;

class Tab
{
     public static function chat(array $navData, $context, $selected)
     {
          $options = \XF::options();
          $visitor = \XF::visitor();

          if (!method_exists($visitor, 'canViewSiropuChat'))
          {
               return;
          }

          if ($options->siropuChatEnabled
               && $options->siropuChatPage
               && $visitor->canViewSiropuChat()
               && !$visitor->isBannedSiropuChat())
          {
               $params = [
                    'title' => \XF::phrase('nav.siropuChat'),
                    'href'  => \XF::app()->router()->buildLink('chat')
               ];

               if ($options->siropuChatNavigationPopup)
               {
                    $params['attributes']['data-xf-click'] = 'siropu-chat-popup';
                    $params['href'] = \XF::app()->router()->buildLink('chat/fullpage');
               }

               if ($options->siropuChatNavUserCount)
               {
                    $activeUserCount = \XF::app()->repository('Siropu\Chat\Repository\User')->getActiveUserCount();

                    if ($options->siropuChatGuestRoom)
                    {
                         $guestServiceManager = \XF::service('Siropu\Chat:Guest\Manager');
                         $activeUserCount += $guestServiceManager->getActiveGuestCount();
                    }

                    $params['counter'] = ' ' . $activeUserCount;
               }

               if ($options->siropuChatNavIcon)
               {
                    $params['icon'] = $options->siropuChatNavIcon;
               }

               return $params;
          }
     }
}
