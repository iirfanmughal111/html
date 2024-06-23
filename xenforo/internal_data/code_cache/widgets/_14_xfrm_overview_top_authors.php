<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('xfrm_overview_top_authors', $__options)->render();

	return $__widget;
};