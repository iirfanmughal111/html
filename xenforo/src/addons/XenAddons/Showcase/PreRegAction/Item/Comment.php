<?php

namespace XenAddons\Showcase\PreRegAction\Item;

use XenAddons\Showcase\PreRegAction\AbstractCommentHandler;

class Comment extends AbstractCommentHandler
{
	public function getContainerContentType(): string
	{
		return 'sc_item';
	}
}