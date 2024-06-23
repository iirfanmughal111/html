<?php
// FROM HASH: 56bacdeda531ebb9f2a08533ee8f2f57
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('reaction_item_sc_page', 'reaction_snippet', array(
		'reactionUser' => $__vars['user'],
		'reactionId' => $__vars['extra']['reaction_id'],
		'page' => $__vars['content'],
		'date' => $__vars['newsFeed']['event_date'],
	), $__vars);
	return $__finalCompiled;
}
);