<?php
// FROM HASH: 9ac56d8612ff53c28a497235222c1c93
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reviewed the product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], 'plain', ), true) . $__templater->escape($__vars['content']['Product']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce/review', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);