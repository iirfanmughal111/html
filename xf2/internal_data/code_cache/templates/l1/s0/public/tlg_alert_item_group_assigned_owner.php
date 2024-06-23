<?php
// FROM HASH: 84271b2a88c3edcbaee3ef1ae4de092c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' assigned you to be owner of the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['name'])) . '</a>') . '';
	return $__finalCompiled;
}
);