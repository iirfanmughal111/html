<?php
// FROM HASH: 2dd9baa54127dadc61ece7be9c722f4a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' replied on comment you are following in the group ' . (((('<a href="' . $__templater->func('link', array('group-comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '.';
	return $__finalCompiled;
}
);