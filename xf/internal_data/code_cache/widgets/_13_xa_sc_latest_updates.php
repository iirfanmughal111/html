<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('xa_sc_latest_updates', $__options)->render();

	return $__widget;
};