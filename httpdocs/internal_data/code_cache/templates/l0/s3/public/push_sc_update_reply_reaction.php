<?php
// FROM HASH: b322b15de9fc4bd2832fccae09b4b3cb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your reply to the update ' . $__templater->escape($__vars['content']['ItemUpdate']['title']) . ' on the item ' . $__templater->escape($__vars['content']['ItemUpdate']['Item']['title']) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '

<push:url>' . $__templater->func('link', array('canonical:showcase/update-reply', $__vars['content'], ), true) . '</push:url>
<push:tag>sc_update_reply_reaction_' . $__templater->escape($__vars['content']['reply_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);