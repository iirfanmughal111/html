<?php
// FROM HASH: 90a433ed29045e622aaeee3bc179edd5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['thread'], 'empty', array()) AND $__templater->method($__vars['thread'], 'canBump', array())) {
		$__finalCompiled .= '
	<a href="' . $__templater->func('link', array('threads/bump', $__vars['thread'], ), true) . '" class="menu-linkRow" data-xf-click="switch" data-menu-closer="true">
		' . $__templater->includeTemplate('bump_thread_button_text', $__vars) . '
	</a>
';
	}
	return $__finalCompiled;
}
);