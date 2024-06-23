<?php

namespace FS\EncryptIp\XF\Repository;
use XF\Mvc\Entity\Repository;

class Banning extends XFCP_Banning
{

	protected function encryptionService(){
		return \xf::app()->service('FS\EncryptIp:Encryption');
	  }
	  
      public function banIp($ip, $reason = '', \XF\Entity\User $user = null)
      {
          $encryption = $this->encryptionService();
      
          //$ip = $encryption->decrypt_ip($ip);
          
          $user = $user ?: \XF::visitor();
  
          list($niceIp, $firstByte, $startRange, $endRange) = $this->getIpRecord($ip);
        
          $ipBan = $this->em->create('XF:IpMatch');
          $ipBan->ip =  $encryption->encrypt_ip($niceIp);
          $ipBan->match_type = 'banned';
          $ipBan->first_byte = $encryption->encrypt_ip($firstByte); 
          $ipBan->start_range =  $encryption->encrypt_ip($startRange);
          $ipBan->end_range =  $encryption->encrypt_ip($endRange);
          $ipBan->reason = $reason;
          $ipBan->create_user_id = $user->user_id;
  
          return $ipBan->save();
      }



      public function getDecrytedIp($dec_val){
     
        $encryption = $this->encryptionService();
      
        return $encryption->decrypt_ip($dec_val); 
    
    }
	public function discourageIp($ip, $reason = '', \XF\Entity\User $user = null)
	{
        $encryption = $this->encryptionService();
          
        //$ip = $encryption->decrypt_ip($ip);
        
		$user = $user ?: \XF::visitor();
        

		list($niceIp, $firstByte, $startRange, $endRange) = $this->getIpRecord($ip);

		$discouragedIp = $this->em->create('XF:IpMatch');
		$discouragedIp->ip = $encryption->encrypt_ip($niceIp);
		$discouragedIp->match_type = 'discouraged';
		$discouragedIp->first_byte = $encryption->encrypt_ip($firstByte);
		$discouragedIp->start_range = $encryption->encrypt_ip($startRange);
		$discouragedIp->end_range = $encryption->encrypt_ip($endRange);
		$discouragedIp->reason = $reason;
		$discouragedIp->create_user_id = $user->user_id;

		return $discouragedIp->save();
	}
	
}