<?php
// FROM HASH: 41920ee1586f5274ad2ddaf73f4fc0d0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' approved your request to join the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content']['Group'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '.';
	return $__finalCompiled;
}
);