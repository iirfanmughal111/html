<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Message extends Repository
{
     public function findRoomMessages($roomId)
     {
          return $this->finder('Siropu\Chat:Message')->fromRoom($roomId);
     }
     public function findMessages($order = null, $direction = 'DESC')
     {
		$finder = $this->finder('Siropu\Chat:Message')->with('User');

          if ($order)
          {
               $finder->order($order, $direction);
          }
          else
          {
               $finder->order('message_id', 'DESC');
          }

          return $finder;
     }
     public function getTopChatters($from = 'today', $limit = 10)
     {
          $simpleCache = $this->app()->simpleCache();
          $topChatters = $simpleCache['Siropu/Chat']['topChatters'];

          switch ($from)
		{
			case 'today':
				$start = strtotime('-' . date('G') . ' Hours');
				$end   = time();
				break;
			case 'yesterday':
				$start = strtotime('-1 Day 00:00');
				$end   = strtotime('-1 Day 23:59');
				break;
			case 'thisWeek':
				$start = strtotime('This Week Monday');
				$end   = time();
				break;
			case 'thisMonth':
				$start = strtotime('first day of this month 00:00');
				$end   = time();
				break;
			case 'lastWeek':
				$start = strtotime('-1 Week Last Monday');
				$end   = strtotime('-1 Week Sunday 23:59');
				break;
			case 'lastMonth':
				$start = strtotime('first day of last month 00:00');
				$end   = strtotime('last day of last month 23:59');
				break;
		}

          if (isset($topChatters[$from]))
          {
               $cache = $topChatters[$from];

               if ($cache['lastUpdate'] >= \XF::$time - 3600)
               {
                    return $this->populateTopChattersResults($cache['results']);
               }
          }

          $results = $this->db()->fetchAllKeyed('
			SELECT u.*,COUNT(*) AS message_count
			FROM xf_siropu_chat_message AS m
			LEFT JOIN xf_user AS u ON u.user_id = m.message_user_id
			WHERE m.message_date BETWEEN ? AND ?
			AND m.message_type IN ("chat", "me")
               AND message_count > 0
			GROUP BY m.message_user_id
			ORDER BY message_count DESC
			LIMIT ?', 'user_id', [$start, $end, $limit]
          );

          if ($results)
          {
               $topChatters[$from] = [
                    'results'    => $results,
                    'lastUpdate' => \XF::$time
               ];

               $simpleCache['Siropu/Chat']['topChatters'] = $topChatters;
          }

          return $this->populateTopChattersResults($results);
     }
     public function populateTopChattersResults(array $users = [])
     {
          $entities = [];

          foreach ($users as $user)
          {
               $entity = $this->em->instantiateEntity('XF:User', $user);
               $entity->siropu_chat_message_count = $user['message_count'];

               $entities[] = $entity;
          }

          return $entities;
     }
     public function pruneAll()
     {
          $this->db()->emptyTable('xf_siropu_chat_message');
          $this->db()->delete('xf_edit_history', 'content_type = ?', 'siropu_chat_room_message');
          $this->db()->delete('xf_reaction_content', 'content_type = ?', 'siropu_chat_room_message');
     }
     public function pruneRoomMessages($roomId)
     {
          $this->db()->delete('xf_siropu_chat_message', 'message_room_id = ?', $roomId);
          $this->setJob('room:' . $roomId);
     }
     public function pruneRoomUserMessages($roomId, $userId)
     {
          $this->db()->delete('xf_siropu_chat_message', 'message_room_id = ? AND message_user_id = ?', [$roomId, $userId]);
          $this->setJob('user:' . $userId);
     }
     public function pruneUserMessages($userId)
     {
          $this->db()->delete('xf_siropu_chat_message', 'message_user_id = ?', $userId);
          $this->db()->delete('xf_reaction_content', 'content_type = ? AND content_user_id = ?', ['siropu_chat_room_message', $userId]);
     }
     public function pruneTypeMessages($type)
     {
          $this->db()->delete('xf_siropu_chat_message', 'message_type IN (' . $this->db()->quote($type) . ')');
          $this->setJob(is_array($type) ? 'forum' : $type);
     }
     public function deleteMessagesByIds($ids)
     {
          $this->db()->delete('xf_siropu_chat_message', 'message_id IN (' . $this->db()->quote($ids) . ')');
          $this->fastDeleteReactions($ids);
     }
     public function deleteMessagesOlderThan($timeFrame)
     {
          $this->db()->delete('xf_siropu_chat_message', 'message_date <= ?', $timeFrame);
          $this->setJob($timeFrame);
     }
     public function deleteMessagesByTypeId($type, $typeId)
     {
          $this->db()->delete('xf_siropu_chat_message', 'message_type = ? AND message_type_id = ?', [$type, $typeId]);
          $this->setJob($type);
     }
     public function deleteIgnoredMessages()
     {
          $this->db()->delete('xf_siropu_chat_message', 'message_is_ignored = 1');
     }
     public function pruneLastXRoomMessages($roomId, $num)
     {
          $ids = array_keys($this->db()->fetchAllKeyed('
               SELECT message_id
               FROM xf_siropu_chat_message
               WHERE message_room_id = ?
               ORDER BY message_date DESC
               LIMIT ' . $num . '
          ', 'message_id', $roomId));

          $this->db()->delete('xf_siropu_chat_message', 'message_id IN (' . $this->db()->quote($ids) . ')');
          $this->fastDeleteReactions($ids);

          return $ids;
     }
     public function setJob($type)
     {
          if (\XF::options()->siropuChatReactions)
          {
               \XF::app()->jobManager()->enqueueUnique(
                    "siropuChatReaction:{$type}",
                    'Siropu\Chat:Reaction',
                    [],
                    false
               );
          }
     }
     protected function fastDeleteReactions($ids)
     {
          /** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('siropu_chat_room_message', $ids, false);
     }
}
