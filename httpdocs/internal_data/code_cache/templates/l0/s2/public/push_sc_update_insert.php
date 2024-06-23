<?php
// FROM HASH: 943670b956b64e8d932dd5935058fb73
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' updated the item ' . ($__templater->func('prefix', array('sc_item', $__vars['content']['Item'], 'plain', ), true) . $__templater->escape($__vars['content']['Item']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:showcase/update', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);