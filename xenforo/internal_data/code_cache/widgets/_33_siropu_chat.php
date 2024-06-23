<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('siropu_chat', $__options)->render();

	return $__widget;
};