<?php
// FROM HASH: 33406dcd2d8c25de5b1391f6aaa3bca0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' has shared their album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . ' with you.';
	return $__finalCompiled;
}
);