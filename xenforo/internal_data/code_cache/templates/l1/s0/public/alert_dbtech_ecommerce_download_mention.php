<?php
// FROM HASH: 2739b2edcfd32c8d9d40e1965bc2241c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in the download ' . (((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/release', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . ' for product ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['content']['Product'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], ), true)) . $__templater->escape($__vars['content']['Product']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);