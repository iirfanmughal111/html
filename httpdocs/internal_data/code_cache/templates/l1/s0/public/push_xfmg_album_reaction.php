<?php
// FROM HASH: 236ac7524d6b41b70a92c44ef8f89086
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your album ' . $__templater->escape($__vars['content']['title']) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:media/albums', $__vars['content'], ), true) . '</push:url>
<push:tag>xfmg_album_reaction_' . $__templater->escape($__vars['content']['album_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);