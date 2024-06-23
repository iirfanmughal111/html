<?php
// FROM HASH: 3b01eb301e2d8d80bcd81197f0d03d3d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['thread'], 'empty', array()) AND $__templater->method($__vars['thread'], 'canBump', array())) {
		$__finalCompiled .= '
	' . $__templater->button('
		' . $__templater->includeTemplate('bump_thread_button_text', $__vars) . '
	', array(
			'href' => $__templater->func('link', array('threads/bump', $__vars['thread'], ), false),
			'data-xf-click' => 'switch',
			'class' => 'button--link',
		), '', array(
		)) . '
';
	}
	return $__finalCompiled;
}
);