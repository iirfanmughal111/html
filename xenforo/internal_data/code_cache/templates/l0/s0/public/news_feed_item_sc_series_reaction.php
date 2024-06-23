<?php
// FROM HASH: 32162e061921f752ada5f117f9216651
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('reaction_item_sc_series', 'reaction_snippet', array(
		'reactionUser' => $__vars['user'],
		'reactionId' => $__vars['extra']['reaction_id'],
		'seriesItem' => $__vars['content'],
		'date' => $__vars['newsFeed']['event_date'],
	), $__vars);
	return $__finalCompiled;
}
);