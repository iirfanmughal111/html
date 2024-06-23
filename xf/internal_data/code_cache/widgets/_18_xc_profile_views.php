<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('xc_profile_views', $__options)->render();

	return $__widget;
};