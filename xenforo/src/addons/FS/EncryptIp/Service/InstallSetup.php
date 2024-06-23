<?php


namespace FS\EncryptIp\Service;

use XF\Service\AbstractService;


class InstallSetup extends AbstractService
{
    protected function encryptionService(){
		return \xf::app()->service('FS\EncryptIp:Encryption');
	}
    public function copyData(){
   
        
        $query = "UPDATE `xf_ip` SET ip_backup = ip";

        $execute  = \XF::db()->query($query);
        
        $Emptyquery = "UPDATE `xf_ip` SET ip = ''";

        $execute  = \XF::db()->query($Emptyquery);
        
        $Matchquery = "UPDATE `xf_ip_match` SET first_byte_backup = first_byte ,start_range_backup = start_range, end_range_backup = end_range";

        $Matchexecute  = \XF::db()->query($Matchquery);

        $EmptyMatchquery = "UPDATE `xf_ip_match` SET first_byte = '' ,start_range = '', end_range = ''";
        
        $Matchexecute  = \XF::db()->query($EmptyMatchquery);
        
    //    $ips = $this->finder('XF:Ip')->fetch();
    //    foreach ($ips as $ip){
    //         $ip->backup_ip = $ip->ip;
    //         $ip->save();
    
    //     }

    //     $ipsMatch = $this->finder('XF:IpMatch')->fetch();
    //    foreach ($ipsMatch as $ipM){
    //         $ipM->first_byte_backup = $ipM->first_byte;
    //         $ipM->start_range_backup = $ipM->start_range;
    //         $ipM->end_range_backup = $ipM->end_range;
    //         $ipM->save();
    //     }
        
        return true;
        
    }

    public function encryptAllData(){
        $encryption = $this->encryptionService(); 
        $ips = $this->finder('XF:Ip')->fetch();
       foreach ($ips as $ip){
            $ip->ip = $encryption->encrypt_ip($ip->ip_backup);
            $ip->ip_backup = NULL;
            $ip->save();
        }

        $ipsMatch = $this->finder('XF:IpMatch')->fetch();
       foreach ($ipsMatch as $ipM){
            $ipM->first_byte = $encryption->encrypt_ip($ipM->first_byte_backup);
            $ipM->start_range = $encryption->encrypt_ip($ipM->start_range_backup);
            $ipM->end_range = $encryption->encrypt_ip($ipM->end_range_backup);
            //$ipM->first_byte_backup = NULL;
         //   $ipM->start_range_backup = NULL;
           // $ipM->end_range_backup = NULL;
            $ipM->save();
    
        }
        
        return true;
           
           
       }
       public function decryptAllData(){
        $encryption = $this->encryptionService(); 
        $ips = $this->finder('XF:Ip')->fetch();
       foreach ($ips as $ip){
            $ip->ip = $encryption->decrypt_ip($ip->ip_backup);
            
            $ip->save();
        }

        $ipsMatch = $this->finder('XF:IpMatch')->fetch();
       foreach ($ipsMatch as $ipM){
            $ipM->first_byte = $encryption->decrypt_ip($ipM->first_byte_backup);
            $ipM->start_range = $encryption->decrypt_ip($ipM->start_range_backup);
            $ipM->end_range = $encryption->decrypt_ip($ipM->end_range_backup);
            $ipM->save();
    
        }
        
        return true;
           
           
       }

       public function restoreData(){

        $query = "UPDATE `xf_ip` SET ip_backup = ip";

        $execute  = \XF::db()->query($query);

        $Matchquery = "UPDATE `xf_ip_match` SET first_byte_backup = first_byte ,start_range_backup = start_range, end_range_backup = end_range";

        $Matchexecute  = \XF::db()->query($Matchquery);
           
        // $ips = $this->finder('XF:Ip')->fetch();
        // foreach ($ips as $ip){
        //      $ip->backup_ip = $ip->ip;
        //      $ip->save();
     
        //  }
 
        //  $ipsMatch = $this->finder('XF:IpMatch')->fetch();
        // foreach ($ipsMatch as $ipM){
        //      $ipM->first_byte_backup = $ipM->first_byte;
        //      $ipM->start_range_backup = $ipM->start_range;
        //      $ipM->end_range_backup = $ipM->end_range;
        //      $ipM->save();
     
        //  }
         
         return true;
         
     }
    
}