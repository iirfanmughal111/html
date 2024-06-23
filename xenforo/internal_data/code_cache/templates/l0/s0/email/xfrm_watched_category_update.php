<?php
// FROM HASH: fbedfceaece011cbc9b396312eb20043
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . '' . ($__templater->func('prefix', array('resource', $__vars['resource'], 'escaped', ), true) . $__templater->escape($__vars['resource']['title'])) . ' updated: ' . $__templater->escape($__vars['update']['title']) . '' . '
</mail:subject>

';
	$__vars['author'] = (($__vars['receiver']['user_id'] == $__vars['resource']['user_id']) ? $__templater->func('username_link_email', array($__vars['update']['TeamUser'], $__vars['update']['team_username'], ), false) : $__templater->func('username_link_email', array($__vars['resource']['User'], $__vars['resource']['username'], ), false));
	$__finalCompiled .= '

' . '<p>' . $__templater->filter($__vars['author'], array(array('raw', array()),), true) . ' updated a resource within a category you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:resources/update', $__vars['update'], ), true) . '">' . $__templater->func('prefix', array('resource', $__vars['resource'], 'escaped', ), true) . $__templater->escape($__vars['resource']['title']) . ' - ' . $__templater->escape($__vars['update']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['update']['message'], 'resource_update', $__vars['update'], ), true) . '</div>
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('xfrm_resource_macros', 'go_resource_bar', array(
		'resource' => $__vars['resource'],
		'watchType' => 'category',
	), $__vars) . '

' . $__templater->callMacro('xfrm_resource_macros', 'watched_category_footer', array(
		'category' => $__vars['category'],
		'resource' => $__vars['resource'],
	), $__vars) . '
';
	return $__finalCompiled;
}
);