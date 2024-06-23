<?php

namespace Siropu\Chat\Pub\Controller;

use XF\Mvc\ParameterBag;

class Top extends AbstractController
{
     public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('siropu_chat_viewing_top_chatters');
	}
     public function actionIndex(ParameterBag $params)
     {
          $visitor = \XF::visitor();

          if (!$visitor->canViewSiropuChatTopChatters())
          {
               return $this->noPermission();
          }

          $from = $this->filter('from', 'str');

          if (!in_array($from, ['today', 'yesterday', 'thisWeek', 'thisMonth', 'lastWeek', 'lastMonth']))
          {
               $from = '';
          }

          $limit = \XF::options()->siropuChatTopChatters;

          if ($from)
          {
               $topChatters = $this->getMessageRepo()->getTopChatters($from, $limit);
          }
          else
          {
               $topChatters = $this->getUserRepo()
                    ->findTopUsers($limit)
                    ->fetch();
          }

          $simpleCache = $this->app()->simpleCache();
          $topChattersCache = $simpleCache['Siropu/Chat']['topChatters'];

          $viewParams = [
               'topChatters' => $topChatters,
               'lastUpdate'  => isset($topChattersCache[$from]) ? $topChattersCache[$from]['lastUpdate'] : \XF::$time,
               'from'        => $from
          ];

          return $this->view('Siropu\Chat:TopChatters', 'siropu_chat_top_chatter_list', $viewParams);
     }
}
