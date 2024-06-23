<?php
// FROM HASH: 700debc3267cd8d6cd5832bf7b3f1ec7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' mentioned you in the download ' . $__templater->escape($__vars['content']['title']) . ' for product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], 'plain', ), true) . $__templater->escape($__vars['content']['Product']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce/release', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);