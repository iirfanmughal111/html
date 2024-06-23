<?php

namespace FS\EncryptIp\XF\Repository;
use XF\Mvc\Entity\Repository;

use function count, intval, strlen;

class Ip extends XFCP_Ip
{

	protected function encryptionService(){
		return \xf::app()->service('FS\EncryptIp:Encryption');
   
	  }
	  
    public function logIp($userId, $ip, $contentType, $contentId, $action = '')
	{
		// parent::(encryption,)
		$encryption = $this->encryptionService(); 
		$encrypted_ip = $encryption->encrypt_ip($ip);
		
		$entity = $this->em->create('XF:Ip');
		$entity->user_id = $userId;
		$entity->ip = $encrypted_ip;
		$entity->content_type = $contentType;
		$entity->content_id = $contentId;
		$entity->action = $action;
		
		if ($entity->save(false))
		{
			return $entity;
		}
		else
		{
			return null;
		}
	}

	public function getUsersByIp($baseIp)
	{
		$encryption = $this->encryptionService(); 
		
		$ip = \XF\Util\Ip::convertIpStringToBinary($baseIp);
		if ($ip === false)
		{
			$baseIp = preg_replace('/[^\x20-\x7F]/', '?', $baseIp);
			throw new \InvalidArgumentException("Cannot convert IP '$baseIp' to binary");
		}
		$baseIp = $encryption->encrypt_ip($baseIp);
		
		$ips = $this->db()->fetchAllKeyed("
			SELECT user_id,
				ip,
				MIN(log_date) AS first_date,
				MAX(log_date) AS last_date,
				COUNT(*) AS total
			FROM xf_ip
			WHERE ip = ?
			GROUP BY user_id
		", 'user_id', $ip);
		if (!$ips)
		{
			return [];
		}

		$userIds = array_column($ips, 'user_id');
		$userIds = array_unique($userIds);

		$userFinder = $this->finder('XF:User')
			->where('user_id', $userIds)
			->order('username');

		$users = $userFinder->fetch();
		$output = [];
		foreach ($users AS $user)
		{
			$ipInfo = $ips[$user->user_id];

			$output[$user->user_id] = [
				'user_id' => $ipInfo['user_id'],
				'ips' => [$ipInfo['ip']],
				'ip_total' => 1,
				'first_date' => $ipInfo['first_date'],
				'last_date' => $ipInfo['last_date'],
				'total' => $ipInfo['total'],
				'user' => $user
			];
		}

		return $output;
	}
	

      public function getDecrytedIp($dec_val){
     
        $encryption = $this->encryptionService();
      
        return $encryption->decrypt_ip($dec_val); 
    
    }

	
}