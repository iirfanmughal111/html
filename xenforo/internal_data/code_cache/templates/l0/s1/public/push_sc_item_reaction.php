<?php
// FROM HASH: c74a4d40c0a320ffa8bc54316b876263
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your item ' . $__templater->escape($__vars['content']['title']) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:showcase', $__vars['content'], ), true) . '</push:url>
<push:tag>sc_item_reaction_' . $__templater->escape($__vars['content']['item_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);