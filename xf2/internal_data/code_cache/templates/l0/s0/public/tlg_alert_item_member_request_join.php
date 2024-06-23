<?php
// FROM HASH: b501a1132c443ac806539ec8efeaefc4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' sent an request to join the group ' . (((('<a href="' . $__templater->func('link', array('groups/members', $__vars['content']['Group'], array('member_state' => 'moderated', ), ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '.';
	return $__finalCompiled;
}
);