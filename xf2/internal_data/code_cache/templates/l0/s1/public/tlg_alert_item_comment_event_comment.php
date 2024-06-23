<?php
// FROM HASH: e25fef225afd0e023f46f6dafbb002b5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['Content']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
    ' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented in your event ' . (((('<a href="' . $__templater->func('link', array('group-events', $__vars['content']['Content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['event_name'])) . '</a>') . ' in the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content']['Group'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '.' . '
';
	} else {
		$__finalCompiled .= '
    ' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented in the event ' . (((('<a href="' . $__templater->func('link', array('group-events', $__vars['content']['Content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['event_name'])) . '</a>') . ' in the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content']['Group'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '.' . '
';
	}
	return $__finalCompiled;
}
);