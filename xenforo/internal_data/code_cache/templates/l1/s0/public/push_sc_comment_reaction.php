<?php
// FROM HASH: 8f6782626481d765bf5e3ec2a95e30d4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your comment on item ' . $__templater->escape($__vars['content']['Content']['title']) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '

<push:url>' . $__templater->func('link', array('canonical:showcase/comments', $__vars['content'], ), true) . '</push:url>
<push:tag>sc_comment_reaction_' . $__templater->escape($__vars['content']['comment_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);