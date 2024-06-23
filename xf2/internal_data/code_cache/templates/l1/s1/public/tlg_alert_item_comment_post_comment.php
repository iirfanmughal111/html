<?php
// FROM HASH: c44704ec9f0d1f2aa74dee1498c6e376
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['Content']['user_id'] === $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
    ' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented on your posts in the group ' . (((('<a href="' . $__templater->func('link', array('group-comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '' . '
';
	} else {
		$__finalCompiled .= '
    ' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented on the post you are following in the group ' . (((('<a href="' . $__templater->func('link', array('group-comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '' . '
';
	}
	return $__finalCompiled;
}
);