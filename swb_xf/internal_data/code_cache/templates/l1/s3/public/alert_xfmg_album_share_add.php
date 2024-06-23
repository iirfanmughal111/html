<?php
// FROM HASH: 3ca69538c1c624dbad25138977262dca
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' has shared their album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . ' with you and you are now able to add media to it.';
	return $__finalCompiled;
}
);