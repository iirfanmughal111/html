<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('dbtech_ecommerce_list_top_products', $__options)->render();

	return $__widget;
};