<?php
// FROM HASH: a68747f79843d656c5fe746ecd48f6b8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['thread'] AND $__templater->method($__vars['thread'], 'canBump', array())) {
		$__finalCompiled .= '
	<li class="structItem-extraInfoMinor">
		<a href="' . $__templater->func('link', array('threads/bump', $__vars['thread'], ), true) . '" data-xf-click="switch">
			' . 'Bump Thread' . '
		</a>
	</li>
';
	}
	return $__finalCompiled;
}
);