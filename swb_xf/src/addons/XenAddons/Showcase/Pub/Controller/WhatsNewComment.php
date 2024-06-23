<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Pub\Controller\AbstractWhatsNewFindType;

class WhatsNewComment extends AbstractWhatsNewFindType
{
	protected function getContentType()
	{
		return 'sc_comment';
	}
}