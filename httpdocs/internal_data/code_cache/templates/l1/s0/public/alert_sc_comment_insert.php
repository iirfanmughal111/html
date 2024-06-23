<?php
// FROM HASH: 361ce55c09628d9e5d6cd01fe37bca03
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . '. There may be more comments after this.';
	return $__finalCompiled;
}
);