<?php
// FROM HASH: c527a5a0fbed699fc5e7e7dec2ba4d2a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' created an event ' . (((('<a href="' . $__templater->func('link', array('group-events', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['event_name'])) . '</a>') . ' in the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content']['Group'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '';
	return $__finalCompiled;
}
);