<?php
// FROM HASH: ace6ae7eccb8bb8e5337494fdf213a5c
return array(
'macros' => array('button' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'buttonClass' => 'button--link',
		'linkParam' => '!',
		'iconClass' => '!',
		'tooltip' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->button('
								<i class="fas fa-' . $__templater->escape($__vars['iconClass']) . '"></i>
							', array(
		'href' => $__templater->func('link', array(('auction/' . $__vars['linkParam']) . '/view-type', ), false),
		'class' => 'button--link',
		'title' => $__vars['tooltip'],
	), '', array(
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);