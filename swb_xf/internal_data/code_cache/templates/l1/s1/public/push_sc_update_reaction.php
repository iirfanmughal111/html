<?php
// FROM HASH: 2725af4202f5cf3b745c03125914d42f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your update ' . $__templater->escape($__vars['content']['title']) . ' on the item ' . $__templater->escape($__vars['content']['Content']['title']) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '

<push:url>' . $__templater->func('link', array('canonical:showcase/update', $__vars['content'], ), true) . '</push:url>
<push:tag>sc_update_reaction_' . $__templater->escape($__vars['content']['item_update_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);