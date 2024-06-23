<?php
// FROM HASH: 522ebcec7752ec49af26ea6da19124fa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' left the item contributors team for the item ' . ($__templater->func('prefix', array('sc_item', $__vars['content'], 'plain', ), true) . $__templater->escape($__vars['content']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:showcase', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);