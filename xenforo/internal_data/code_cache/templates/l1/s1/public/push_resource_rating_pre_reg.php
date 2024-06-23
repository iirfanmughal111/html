<?php
// FROM HASH: c09146cf1ed323486947c49eb858f2ed
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
' . 'Your review to the resource ' . ($__templater->func('prefix', array('resource', $__vars['content']['Resource'], 'plain', ), true) . $__templater->escape($__vars['content']['Resource']['title'])) . ' was submitted.' . '
<push:url>' . $__templater->func('link', array('canonical:resources/review', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);