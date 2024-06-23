<?php
// FROM HASH: 5c8f31b2ad083a47d1f49c1341ac8c01
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Welcome to ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '!' . '
' . 'Your reply to the item ' . $__templater->escape($__vars['content']['Content']['title']) . ' was submitted.' . '
<push:url>' . $__templater->func('link', array('canonical:showcase/comments', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);