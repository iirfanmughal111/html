<?php
// FROM HASH: 3e5b4055447bd278d07026befadc7b67
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['xf']['visitor']['user_id'] == $__vars['content']['ParentPost']['user_id']) {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented your post in the thread ' . ((((('<a href="' . $__templater->func('link', array('posts', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('thread', $__vars['content']['Thread'], ), true)) . $__templater->escape($__vars['content']['Thread']['title'])) . '</a>') . '.' . '
';
	} else {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented ' . $__templater->func('username_link_email', array($__vars['content']['ParentPost']['User'], $__vars['content']['ParentPost']['username'], ), true) . '\'s post in the thread ' . ((((('<a href="' . $__templater->func('link', array('posts', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('thread', $__vars['content']['Thread'], ), true)) . $__templater->escape($__vars['content']['Thread']['title'])) . '</a>') . '.' . '
';
	}
	$__finalCompiled .= '

<push:url>' . $__templater->func('link', array('canonical:posts', $__vars['content'], ), true) . '</push:url>
<push:tag>post_insert_thread_' . $__templater->escape($__vars['content']['thread_id']) . '</push:tag>';
	return $__finalCompiled;
}
);