<?php
// FROM HASH: 3e302faca8aadc7f9061a158a9c90484
return array(
'extends' => function($__templater, array $__vars) { return 'pre_reg_action_approval_queue'; },
'extensions' => array('content' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['details']['rating'],
		'class' => 'ratingStars--smaller',
	), $__vars) . '

	' . $__templater->renderExtensionParent($__vars, null, $__extensions) . '
';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->renderExtension('content', $__vars, $__extensions);
	return $__finalCompiled;
}
);