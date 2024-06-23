<?php
// FROM HASH: b3f3ca378aaebe0a8f7e5f592b605688
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' invited you join the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['name'])) . '</a>') . '.';
	return $__finalCompiled;
}
);