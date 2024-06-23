<?php
// FROM HASH: 3338f516d53cc98c304d5a956f6cfe05
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Welcome to ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '!' . '
' . 'Your reply to the item ' . (((('<a href="' . $__templater->func('link', array('showcase/comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . ' was submitted.';
	return $__finalCompiled;
}
);