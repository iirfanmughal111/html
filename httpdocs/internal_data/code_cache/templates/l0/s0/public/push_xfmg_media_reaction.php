<?php
// FROM HASH: ddbe041e97d342a2eb400f7427a7c40b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your media ' . $__templater->escape($__vars['content']['title']) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:media', $__vars['content'], ), true) . '</push:url>
<push:tag>xfmg_media_reaction_' . $__templater->escape($__vars['content']['media_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);