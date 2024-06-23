<?php
// FROM HASH: d6ff96f13afde3a755a5d5c537ff3f47
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('reaction_item_sc_rating_reply', 'reaction_snippet', array(
		'reactionUser' => $__vars['user'],
		'reactionId' => $__vars['extra']['reaction_id'],
		'reply' => $__vars['content'],
		'date' => $__vars['newsFeed']['event_date'],
	), $__vars);
	return $__finalCompiled;
}
);