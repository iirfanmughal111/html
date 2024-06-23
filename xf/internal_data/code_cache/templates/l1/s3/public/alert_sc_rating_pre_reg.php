<?php
// FROM HASH: e5fa73e2ba758dc3c3732ecfdef871ee
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Welcome to ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '!' . '
' . 'Your review to the item ' . ((((('<a href="' . $__templater->func('link', array('showcase/review', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . ' was submitted.';
	return $__finalCompiled;
}
);