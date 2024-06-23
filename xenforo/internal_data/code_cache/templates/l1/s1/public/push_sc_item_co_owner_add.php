<?php
// FROM HASH: 356a2dfc7f4cf1e601f439f699a0ef98
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' added you as co-owner for the item ' . ($__templater->func('prefix', array('sc_item', $__vars['content'], 'plain', ), true) . $__templater->escape($__vars['content']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:showcase', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);