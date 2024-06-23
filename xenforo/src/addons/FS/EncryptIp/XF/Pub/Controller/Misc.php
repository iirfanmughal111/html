<?php


namespace FS\EncryptIp\XF\Pub\Controller;

use XF\Mvc\ParameterBag;


class Misc extends XFCP_Misc
{

	protected function encryptionService(){
		return \xf::app()->service('FS\EncryptIp:Encryption');
	}
	  

	public function actionIpInfo()
	{
		$encryption = $this->encryptionService(); 
		if (!\XF::visitor()->canViewIps())
		{
			return $this->noPermission();
		}

		$ip = $this->filter('ip', 'str');
		$url = $this->options()->ipInfoUrl;
		$ip = $encryption->decrypt_ip($ip);
		if (strpos($url, '{ip}') === false)
		{
			$url = 'https://whatismyipaddress.com/ip/{ip}';
		}

		return $this->redirectPermanently(str_replace('{ip}', urlencode($ip), $url));
	}
}