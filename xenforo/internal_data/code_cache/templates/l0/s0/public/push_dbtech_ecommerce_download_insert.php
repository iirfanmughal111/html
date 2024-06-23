<?php
// FROM HASH: b6cbe8bdcf866f4eb266aadd25ff7afa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' updated the product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], 'plain', ), true) . $__templater->escape($__vars['content']['Product']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce/release', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);