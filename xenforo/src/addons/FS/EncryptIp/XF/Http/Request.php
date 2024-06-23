<?php


namespace FS\EncryptIp\XF\Http;

//use function array_key_exists, count, floatval, in_array, intval, is_array, is_string, strlen, strval;
use XF\Http\Request;

class Requestss extends Request
{
    public function getIp($allowProxied = false)
	{
       var_dump( 'override');exit;

		if ($allowProxied && $ip = $this->getServer('HTTP_CLIENT_IP'))
		{
			list($ip) = explode(',', $ip);
			return $this->getFilteredIp($ip);
		}
		else if ($allowProxied && $ip = $this->getServer('HTTP_X_FORWARDED_FOR'))
		{
			list($ip) = explode(',', $ip);
			return $this->getFilteredIp($ip);
		}

		if ($this->remoteIp === null)
		{
			$ip = $this->getTrustedRealIp($this->getServer('REMOTE_ADDR'));
			$this->remoteIp = $this->getFilteredIp($ip);
		}
        $encryption = $this->encryptionService(); 
       var_dump( 'here',$encryption->encrypt_ip('$this->remoteIp'));exit;

		return $encryption->encrypt_ip($this->remoteIp);
	}
}