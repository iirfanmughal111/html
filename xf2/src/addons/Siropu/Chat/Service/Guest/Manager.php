<?php

namespace Siropu\Chat\Service\Guest;

class Manager extends \XF\Service\AbstractService
{
     private $cache;
     private $guests;
     private $nickname;
     private $ip;

	public function __construct(\XF\App $app)
	{
		parent::__construct($app);

          $this->cache    = $this->app->simpleCache();
          $this->guests   = $this->cache['Siropu/Chat']['guests'] ?: [];
          $this->nickname = $this->app->session()->get('siropuChatGuestNickname');
          $this->ip       = $this->app->request()->getIp();
	}
     public function getNickname()
     {
          return $this->nickname;
     }
     public function getIp()
     {
          return $this->ip;
     }
     public function getId($nickname)
     {
          if ($nickname)
          {
               return strtolower($nickname);
          }
     }
     public function saveGuest($nickname, $returnEntity = false)
     {
          $nicknameId = $this->getId($nickname);

          $this->guests[$nicknameId] = [
               'nickname'     => $nickname,
               'ip'           => $this->getIp(),
               'lastActivity' => \XF::$time
          ];

          $this->cache['Siropu/Chat']['guests'] = $this->guests;

          if ($returnEntity)
          {
               return $this->getGuestEntity($this->guests[$nicknameId]);
          }
     }
     public function getGuest($nickname = null)
     {
          $nicknameId = $this->getId($nickname ?: $this->nickname);

          return isset($this->guests[$nicknameId]) ? $this->guests[$nicknameId] : null;
     }
     public function checkNicknameAvailability($nickname, &$error = null)
     {
          if ($this->getId($nickname) == $this->getId($this->nickname))
          {
               return false;
          }

          $guest = $this->getGuest($nickname);

          if ($guest && $this->isActive($guest))
          {
               $error = \XF::phrase('siropu_chat_nickname_is_currently_in_use');
               return false;
          }

          return true;
     }
     public function getGuestEntity($guest = null)
     {
          $guest = $guest ?: $this->getGuest();

          if (empty($guest))
          {
               return;
          }

          return $this->app->em()->instantiateEntity('XF:User', [
               'user_id'                   => 0,
               'username'                  => $guest['nickname'],
               'siropu_chat_last_activity' => $guest['lastActivity']
          ]);
     }
     public function getGuestsForDisplay()
     {
          $guests = [];

          foreach ($this->guests as $guest)
          {
               if ($this->isActive($guest))
               {
                    $guests[] = $this->getGuestEntity($guest);

                    $this->app->em()->clearEntityCache('XF:User');
               }
          }

          return $guests;
     }
     public function getActiveGuestCount()
     {
          $count = 0;

          foreach ($this->guests as $guest)
          {
               if ($this->isActive($guest))
               {
                    $count++;
               }
          }

          return $count;
     }
     public function removeGuest($nickname = null)
     {
          $nicknameId = $this->getId($nickname ?: $this->nickname);

          if (isset($this->guests[$nicknameId]))
          {
               unset($this->guests[$nicknameId]);

               $this->cache['Siropu/Chat']['guests'] = $this->guests;
          }
     }
     public function leaveRoom($roomId, $message)
     {
          $guest = $this->getGuest();
          $this->removeGuest();

          if ($this->isActive($guest))
          {
               $notifier = $this->app->service('Siropu\Chat:Room\Notifier', $this->getGuestEntity($guest), $roomId, ['message' => $message]);
               $notifier->notify('leave');
          }
     }
     public function isActive($guest)
     {
          return $guest['lastActivity'] >= $this->repository('Siropu\Chat:User')->getActivityTimeout();
     }
}
