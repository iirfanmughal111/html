<?php

namespace Siropu\Chat\Cron;

class Sanction
{
	public static function deleteExpiredSanctions()
	{
		$sanctions = \XF::finder('Siropu\Chat:Sanction')
			->where('sanction_end', '>', 0)
			->where('sanction_end', '<=', \XF::$time)
			->fetch();

		foreach ($sanctions as $sanction)
		{
			$sanction->delete();

			if ($sanction->User && !$sanction->User->siropuChatGetUserSanctions()->total())
			{
				$sanction->User->siropu_chat_is_sanctioned = 0;
				$sanction->User->save();
			}
		}
     }
}
