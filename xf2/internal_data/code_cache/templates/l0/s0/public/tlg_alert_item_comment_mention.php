<?php
// FROM HASH: 034efea0a3f4e535325353db073f0834
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in a comment in the group ' . (((('<a href="' . $__templater->func('link', array('group-comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '.';
	return $__finalCompiled;
}
);