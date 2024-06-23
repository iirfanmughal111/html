<?php
// FROM HASH: ce4ca35e75c2d9504e94684a2701f67c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['content'], 'isDescription', array())) {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your resource ' . ($__templater->func('prefix', array('resource', $__vars['content']['Resource'], 'plain', ), true) . $__templater->escape($__vars['content']['Resource']['title'])) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '

	<push:url>' . $__templater->func('link', array('canonical:resources', $__vars['content']['Resource'], ), true) . '</push:url>
';
	} else {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your update for resource ' . ($__templater->func('prefix', array('resource', $__vars['content']['Resource'], 'plain', ), true) . $__templater->escape($__vars['content']['Resource']['title'])) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '

	<push:url>' . $__templater->func('link', array('canonical:resources/update', $__vars['content'], ), true) . '</push:url>
';
	}
	$__finalCompiled .= '

<push:tag>resource_update_reaction_' . $__templater->escape($__vars['content']['resource_update_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);