<?php
// FROM HASH: cc30acdf62b3f618c3eea08a178858c4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your reply to a review on the item ' . $__templater->escape($__vars['content']['ItemRating']['Item']['title']) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '

<push:url>' . $__templater->func('link', array('canonical:showcase/review-reply', $__vars['content'], ), true) . '</push:url>
<push:tag>sc_rating_reply_reaction_' . $__templater->escape($__vars['content']['reply_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);