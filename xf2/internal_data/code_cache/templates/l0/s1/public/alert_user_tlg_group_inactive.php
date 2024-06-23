<?php
// FROM HASH: 6057f8ff4e62b8cf2153506e4e29024f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'The group ' . (('<strong>' . $__templater->escape($__vars['extra']['groupName'])) . '</strong>') . ' which you managed has been deleted because it was inactive for ' . $__templater->escape($__vars['extra']['days']) . ' days.';
	return $__finalCompiled;
}
);