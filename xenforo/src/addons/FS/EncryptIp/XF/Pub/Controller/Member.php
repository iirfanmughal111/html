<?php


namespace FS\EncryptIp\XF\Pub\Controller;

use XF\Mvc\ParameterBag;


class Member extends XFCP_Member
{

	protected function encryptionService(){
		return \xf::app()->service('FS\EncryptIp:Encryption');
	}
	  
	// public function actionIpUsers()
	// {
	// 	/** @var \XF\Repository\Ip $ipRepo */
	// 	$ipRepo = $this->repository('XF:Ip');
	// 	$encryption = $this->encryptionService(); 
	// 	$ip = $this->filter('ip', 'str');
	// 	$ip = $encryption->decrypt_ip($ip);
	// 	$parsed = \XF\Util\Ip::parseIpRangeString($ip);
	// 	//var_dump($parsed);exit;
		
	// 	if (!$parsed)
	// 	{
	// 		return $this->message(\XF::phrase('please_enter_valid_ip_or_ip_range'));
	// 	}
	// 	else if ($parsed['isRange'])
	// 	{
	// 		//$parsed['startRange'] =  $encryption->encrypt_ip($parsed['startRange']);
	// 		//$parsed['endRange'] =  $encryption->encrypt_ip($parsed['endRange']);
	// 		$ips = $ipRepo->getUsersByIpRange($parsed['startRange'], $parsed['endRange']);
	// 	}
	// 	else
	// 	{
	// 		//$parsed['startRange'] =  $encryption->encrypt_ip($parsed['startRange']);
	// 		$ips = $ipRepo->getUsersByIp($parsed['startRange']);
	// 	}
	// 	if ($ips)
	// 	{
	// 		$viewParams = [
	// 			'ip' => $encryption->encrypt_ip($ip),
	// 			'ipParsed' => $parsed,
	// 			'ipPrintable' => $encryption->encrypt_ip($parsed['printable']),
	// 			'ips' => $ips
	// 		];
	// 		return $this->view('XF:User\IpUsers\Listing', 'ip_users_list', $viewParams);
	// 	}
	// 	else
	// 	{
	// 		return $this->message(\XF::phrase('no_users_logged_at_ip'));
	// 	}
	// }


	public function actionIpUsers()
	{
		if (!\XF::visitor()->canViewIps())
		{
			return $this->noPermission();
		}

		/** @var \XF\Repository\Ip $ipRepo */
		$ipRepo = $this->repository('XF:Ip');

		$encryption = $this->encryptionService(); 
	 	$ip = $this->filter('ip', 'str');
	 	$ip = $encryption->decrypt_ip($ip);
		$parsed = \XF\Util\Ip::parseIpRangeString($ip);

		if (!$parsed)
		{
			return $this->message(\XF::phrase('please_enter_valid_ip_or_ip_range'));
		}
		else if ($parsed['isRange'])
		{
			$ips = $ipRepo->getUsersByIpRange($parsed['startRange'], $parsed['endRange']);
		}
		else
		{
			$ips = $ipRepo->getUsersByIp($parsed['startRange']);
		}

		if ($ips)
		{
			$viewParams = [
				'ip' => $encryption->encrypt_ip($ip),
				'ipParsed' => $parsed,
				'ipPrintable' => $encryption->encrypt_ip($parsed['printable']),
				'ips' => $ips
			];
			return $this->view('XF:Member\IpUsers', 'member_ip_users_list', $viewParams);
		}
		else
		{
			return $this->message(\XF::phrase('no_users_logged_at_ip'));
		}
	}
}