<?php
// FROM HASH: 666418325a7d47eee25d9869702b8769
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('reaction_item_resource_update', 'reaction_snippet', array(
		'reactionUser' => $__vars['user'],
		'reactionId' => $__vars['extra']['reaction_id'],
		'update' => $__vars['content'],
		'date' => $__vars['newsFeed']['event_date'],
	), $__vars);
	return $__finalCompiled;
}
);