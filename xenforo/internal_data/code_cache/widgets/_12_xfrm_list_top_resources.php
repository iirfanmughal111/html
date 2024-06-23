<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('xfrm_list_top_resources', $__options)->render();

	return $__widget;
};