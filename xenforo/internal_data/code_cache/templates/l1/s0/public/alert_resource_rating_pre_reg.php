<?php
// FROM HASH: 79875668d0637d0ac529f3b1347c6bc9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['extra']['welcome']) {
		$__finalCompiled .= '
	' . 'Welcome to ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '!' . '
';
	}
	$__finalCompiled .= '
' . 'Your review to the resource ' . ((((('<a href="' . $__templater->func('link', array('resources/review', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('resource', $__vars['content']['Resource'], ), true)) . $__templater->escape($__vars['content']['Resource']['title'])) . '</a>') . ' was submitted.';
	return $__finalCompiled;
}
);