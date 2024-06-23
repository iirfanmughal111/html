<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Sanction extends Repository
{
     public function findRoomUserSanction($roomId, $userId)
     {
		return $this->finder('Siropu\Chat:Sanction')
               ->forRoom($roomId)
               ->forUser($userId)
               ->fetchOne();
     }
     public function findSanctions($order = 'sanction_start', $direction = 'DESC')
     {
		return $this->finder('Siropu\Chat:Sanction')
               ->with('User')
               ->with('Room')
               ->order($order, $direction);
     }
     public function findUserSanctions($userId)
     {
		return $this->findSanctions()->where('sanction_user_id', $userId);
     }
     public function removeAllUserSanctions($userId)
	{
		$this->db()->delete('xf_siropu_chat_sanction', 'sanction_user_id = ?', $userId);
	}
     public function deleteRoomSanctions($roomId)
     {
          $this->db()->delete('xf_siropu_chat_sanction', 'sanction_room_id = ?', $roomId);
     }
     public function deleteExpiredSanctions()
     {
          $this->db()->delete('xf_siropu_chat_sanction', 'sanction_end > 0 AND sanction_end <= ?', \XF::$time);
     }
}
