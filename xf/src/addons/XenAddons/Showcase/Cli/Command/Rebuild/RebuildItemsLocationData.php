<?php

namespace XenAddons\Showcase\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildItemsLocationData extends AbstractRebuildCommand
{
	protected function getRebuildName()
	{
		return 'xa-sc-items-location-data';
	}

	protected function getRebuildDescription()
	{
		return 'Rebuilds showcase item location data.';
	}

	protected function getRebuildClass()
	{
		return 'XenAddons\Showcase:ItemLocationData';
	}
}