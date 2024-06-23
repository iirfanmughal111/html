<?php
// FROM HASH: 9b2fc4962302a9975ce53ec394f54453
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Welcome to ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '!' . '
' . 'Your review to the item ' . ($__templater->func('prefix', array('sc_item', $__vars['content']['Item'], 'plain', ), true) . $__templater->escape($__vars['content']['Item']['title'])) . ' was submitted.' . '
<push:url>' . $__templater->func('link', array('canonical:showcase/review', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);