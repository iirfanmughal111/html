<?php
// FROM HASH: 25a1bb4bb710805586ba069872009c17
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in the product ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content'], ), true)) . $__templater->escape($__vars['content']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);