<?php
// FROM HASH: 41b2f65a9c0540710a65e8b7ae152a83
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' created the product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content'], 'plain', ), true) . $__templater->escape($__vars['content']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);