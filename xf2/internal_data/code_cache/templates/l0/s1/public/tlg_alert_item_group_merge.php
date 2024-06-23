<?php
// FROM HASH: 31c0de028fda54a610588a7f48474e57
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' merged the group ' . $__templater->escape($__vars['extra']['name']) . ' into the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['name'])) . '</a>') . '.';
	return $__finalCompiled;
}
);