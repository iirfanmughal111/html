<?php
// FROM HASH: 1fcc354d461aca98d5a232cf076d42ac
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('
    ' . 'Group <a href=' . $__templater->func('link', array('groups', $__vars['content'], ), true) . '>' . $__templater->escape($__vars['content']['name']) . '</a> published in the category <a href=' . $__templater->func('link', array('group-categories', $__vars['content']['Category'], ), true) . '>' . $__templater->escape($__vars['content']['Category']['title']) . '</a>' . '
', array(
		'label' => 'Group',
	)) . '

' . $__templater->formRow('
    ' . $__templater->func('username_link', array($__vars['content']['User'], true, array(
		'defaultname' => $__vars['content']['owner_username'],
	))) . '
', array(
		'label' => 'Owner',
	)) . '

' . $__templater->formRow('
    ' . $__templater->func('date_dynamic', array($__vars['content']['created_date'], array(
	))) . '
', array(
		'label' => 'Post date',
	)) . '

' . $__templater->callMacro('approval_queue_macros', 'spam_log', array(
		'spamDetails' => $__vars['spamDetails'],
	), $__vars) . '

' . $__templater->formRow('
    ' . $__templater->func('bb_code', array($__vars['content']['description'], 'tl_group', $__vars['content'], ), true) . '
', array(
		'label' => 'Description',
	)) . '

' . $__templater->callMacro('approval_queue_macros', 'action_row', array(
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
	), $__vars);
	return $__finalCompiled;
}
);