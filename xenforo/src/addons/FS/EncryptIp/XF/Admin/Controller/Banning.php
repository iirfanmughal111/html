<?php


namespace FS\EncryptIp\XF\Admin\Controller;

use XF\Mvc\ParameterBag;


class Banning extends XFCP_Banning
{

	protected function encryptionService(){
		return \xf::app()->service('FS\EncryptIp:Encryption');
	}
	  
    public function actionIpsAdd()
	{
		$encryption = $this->encryptionService(); 
		
		//$encrypted_ip = $encryption->encrypt_ip($ip);
		// $a = $this->finder('XF:IpMatch')->fetchOne();
		// var_dump($a->ip);exit;
		$route = \XF::app()->request()->getRoutePath();
		$ip = $this->filter('ip', 'str');
		//var_dump($this->isPost());exit;
		

		//$ip = $encryption->decrypt_ip($ip);
		
		if ($this->isPost())
		{
			$this->getBanningRepo()->banIp(
				$this->filter('ip', 'str'),
				$this->filter('reason', 'str')
			);
			return $this->redirect($this->buildLink('banning/ips'));
		}
		else
		{	
			
			$ipEntity = $this->em()->create('XF:IpMatch');
			$ipEntity->set('ip', $ip);
			$viewParams = [
				'ip' => $ipEntity
			];
			return $this->view('XF:Banning\Ip\Add', 'ban_ip', $viewParams);
		}
	}

	public function actionDiscouragedIpsAdd()
	{
		$encryption = $this->encryptionService(); 
		
		$ip = $this->filter('ip', 'str');
		$temp_ip = $ip;

//		$ip = $encryption->decrypt_ip($temp_ip);

		if ($this->isPost())
		{
			$this->getBanningRepo()->discourageIp(
				$this->filter('ip', 'str'),
				$this->filter('reason', 'str')
			);
			return $this->redirect($this->buildLink('banning/discouraged-ips'));
		}
		else
		{
			$viewParams = [
				'ip' => $ip
			];
			return $this->view('XF:Banning\DiscouragedIp\Add', 'discourage_ip', $viewParams);
		}
	}
}