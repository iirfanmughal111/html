<?php
// FROM HASH: 4723f095a143b20bc1def3410239faf3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('reaction_item_dbtech_ecommerce_download', 'reaction_snippet', array(
		'reactionUser' => $__vars['user'],
		'reactionId' => $__vars['extra']['reaction_id'],
		'download' => $__vars['content'],
		'date' => $__vars['newsFeed']['event_date'],
	), $__vars);
	return $__finalCompiled;
}
);