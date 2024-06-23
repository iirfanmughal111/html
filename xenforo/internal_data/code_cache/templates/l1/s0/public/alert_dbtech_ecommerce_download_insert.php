<?php
// FROM HASH: 5196de9e5564534c50571744547cfaf2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' updated the product ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/release', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], ), true)) . $__templater->escape($__vars['content']['Product']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);