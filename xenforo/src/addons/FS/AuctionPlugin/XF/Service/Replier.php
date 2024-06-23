<?php

namespace FS\AuctionPlugin\XF\Service;

use XF\Entity\Thread;

class Replier extends XFCP_Replier
{
	public function __construct(\XF\App $app, Thread $thread, $user = '')
	{

		parent::__construct($app, $thread);
		$this->setThread($thread);
		$this->setUser($user ? $user : \XF::visitor());
		$this->setPostDefaults();
	}
}
