<?php
// FROM HASH: caf830a0bb06ba38758f7cd095a75f30
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' promoted you to ' . $__templater->escape($__templater->method($__vars['content']['MemberRole'], 'getTitle', array())) . ' in the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content']['Group'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '';
	return $__finalCompiled;
}
);