<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Pub\Controller\AbstractWhatsNewFindType;

class WhatsNewItem extends AbstractWhatsNewFindType
{
	protected function getContentType()
	{
		return 'sc_item';
	}
}