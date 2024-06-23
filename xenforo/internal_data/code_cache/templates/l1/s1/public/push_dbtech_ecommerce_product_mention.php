<?php
// FROM HASH: d94e04862c81d7c0ddfb2d4ab89976c2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' mentioned you in the product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content'], 'plain', ), true) . $__templater->escape($__vars['content']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);