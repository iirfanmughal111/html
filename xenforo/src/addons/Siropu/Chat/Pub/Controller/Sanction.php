<?php

namespace Siropu\Chat\Pub\Controller;

use XF\Mvc\ParameterBag;

class Sanction extends AbstractController
{
     public function actionIndex(ParameterBag $params)
     {
          $viewParams = [
               'sanction' => $this->em()->create('Siropu\Chat:Sanction'),
               'rooms'    => $this->finder('Siropu\Chat:Room')->order('room_name', 'ASC')->fetch(),
          ];

          if ($params->room_id)
          {
               $viewParams['roomId'] = $params->room_id;
          }

          if ($params->user_id)
          {
               $viewParams['user'] = $this->assertUserExists($params->user_id);
          }

          return $this->view('Siropu\Chat:Sanction\Apply', 'siropu_chat_sanction_apply', $viewParams);
     }
     public function actionApply(ParameterBag $params)
     {
          $input = $this->filter([
               'user_id'       => 'uint',
               'username'      => 'str',
               'room_id'       => 'array-uint',
               'sanction_type' => 'str',
               'method'        => 'str',
               'length_type'   => 'str',
			'length_value'  => 'uint',
			'length_option' => 'str',
               'length_date'   => 'datetime',
               'reason'        => 'str'
          ]);

          if ($input['method'] == 'chat')
          {
               $input['room_id'] = 0;
          }

          if ($input['length_type'] == 'perm')
          {
               $endDate = 0;
          }
          else
          {
               if ($input['length_date'])
               {
                    $endDate = $input['length_date'];
               }
               else
               {
                    $endDate = strtotime("+{$input['length_value']} {$input['length_option']}");
               }
          }

          $data = [
               'end_date' => $endDate,
               'reason'   => $input['reason']
          ];

          if ($params->sanction_id)
          {
               $sanction = $this->assertSanctionExists($params->sanction_id, 'User');
               $this->applySanction($sanction->User, $sanction, $input['sanction_type'], $data);

               return $this->redirect($this->buildLink('chat/sanctions'));
          }

          $users = null;

          if ($input['user_id'])
          {
               $users = $this->finder('XF:User')
                    ->where('user_id', $input['user_id'])
                    ->fetch();
          }

          if ($input['username'])
          {
               $users = $this->finder('XF:User')
                    ->where('username', array_map('trim', explode(',', $input['username'])))
                    ->fetch();
          }

          if (!$users)
          {
               return $this->error(\XF::phrase('requested_user_not_found'));
          }

          $rooms = [];

          if (is_array($input['room_id']))
          {
               $rooms = $input['room_id'];
          }
          else
          {
               $rooms = [$input['room_id']];
          }

          foreach ($users as $user)
          {
               foreach ($rooms as $roomId)
               {
                    $this->applySanction($user, null, $input['sanction_type'], array_merge($data, ['room_id' => $roomId]));
               }
          }

          return $this->redirect($this->buildLink('chat/sanctions'));
     }
     public function actionLift(ParameterBag $params)
     {
          $visitor = \XF::visitor();

          if (!$visitor->canSanctionSiropuChat())
          {
               return $this->noPermission();
          }

          $sanction = $this->assertSanctionExists($params->sanction_id, 'User');

          if ($this->isPost())
          {
               $sanctionService = $this->service('Siropu\Chat:Sanction\Manager', $sanction->User, $sanction);
               $sanctionService->liftSanction();

               return $this->redirect($this->buildLink('chat/sanctions'));
          }

          $viewParams = [
               'sanction' => $sanction
          ];

          return $this->view('Siropu\Chat:Sanction\Lift', 'siropu_chat_sanction_lift', $viewParams);
     }
     public function actionEdit(ParameterBag $params)
     {
          $viewParams = [
               'sanction' => $this->assertSanctionExists($params->sanction_id),
               'rooms'    => $this->finder('Siropu\Chat:Room')->order('room_name', 'ASC')->fetch(),
          ];

          return $this->view('', 'siropu_chat_sanction_apply', $viewParams);
     }
     protected function applySanction($user = null, $sanction = null, $type = 'ban', array $data = [])
     {
          $visitor = \XF::visitor();

          if (!$visitor->canSanctionSiropuChat())
          {
               return $this->noPermission();
          }

          $sanctionService = $this->service('Siropu\Chat:Sanction\Manager', $user, $sanction, $data);
          $sanctionService->applySanction($type);
     }
     public function actionSanctions()
     {
          $visitor = \XF::visitor();

          if (!$visitor->canViewSiropuChatSanctions())
          {
               return $this->noPermission();
          }

          $type   = $this->filter('type', 'str');
          $user   = $this->filter('username', 'str');

          $finder = $this->getSanctionRepo()->findSanctions();

          switch ($type)
          {
               case 'ban':
                    $finder->forType('ban');
                    break;
               case 'kick':
                    $finder->forType('kick');
                    break;
               case 'mute':
                    $finder->forType('mute');
                    $finder->where('sanction_user_id', '<>', $visitor->user_id);
                    break;
               default:
                    break;

          }

          if ($user)
          {
               $userData = $this->finder('XF:User')->where('username', $user)->fetchOne();

               if (!$userData)
               {
                    return $this->error('requested_user_not_found');
               }

               $finder->forUser($userData->user_id);
          }

          $page    = $this->filterPage();
          $perPage = 50;

          $finder->limitByPage($page, $perPage);

          $viewParams = [
               'sanctions'  => $finder->fetch(),
               'total'      => $finder->total(),
               'page'       => $page,
               'perPage'    => $perPage,
               'type'       => $type
          ];

          return $this->view('', 'siropu_chat_sanction_list', $viewParams);
     }
     protected function assertSanctionExists($id = null, $with = null)
     {
		return $this->assertRecordExists('Siropu\Chat:Sanction', $id, $with, 'siropu_chat_requested_sanction_not_found');
     }
}
