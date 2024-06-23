<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('tlg_groups_mostViewed', $__options)->render();

	return $__widget;
};