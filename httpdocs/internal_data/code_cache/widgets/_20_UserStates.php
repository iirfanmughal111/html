<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('UserStates', $__options)->render();

	return $__widget;
};