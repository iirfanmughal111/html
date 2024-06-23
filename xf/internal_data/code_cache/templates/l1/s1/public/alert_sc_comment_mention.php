<?php
// FROM HASH: 9db0e68b7a6120ba37d88cbaebf70c7d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in a comment on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);