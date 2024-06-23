<?php
// FROM HASH: fab49ba4e22f19a7d3c3c1a5471e667f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' published a post in the group ' . (((('<a href="' . $__templater->func('link', array('group-comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . '. There may be more posts after this.';
	return $__finalCompiled;
}
);