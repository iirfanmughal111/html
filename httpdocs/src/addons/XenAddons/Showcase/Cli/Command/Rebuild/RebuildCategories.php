<?php

namespace XenAddons\Showcase\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildCategories extends AbstractRebuildCommand
{
	protected function getRebuildName()
	{
		return 'xa-sc-categories';
	}

	protected function getRebuildDescription()
	{
		return 'Rebuilds showcase category counters.';
	}

	protected function getRebuildClass()
	{
		return 'XenAddons\Showcase:Category';
	}
}