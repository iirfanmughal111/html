<?php
// FROM HASH: 07bbe3f115e71124d293f9249d1989ac
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('account_privacy', 'privacy_option', array(
		'user' => $__vars['xf']['visitor'],
		'name' => 'allow_view_users_who_viewed_profile',
		'label' => 'View the users who viewed your profile.' . $__vars['xf']['language']['label_separator'],
	), $__vars);
	return $__finalCompiled;
}
);