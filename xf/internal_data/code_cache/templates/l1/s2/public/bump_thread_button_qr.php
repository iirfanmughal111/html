<?php
// FROM HASH: 60130db321557b073ca632e191604334
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
			'class' => 'button--primary',
		), '', array(
		)) . '
';
	}
	return $__finalCompiled;
}
);