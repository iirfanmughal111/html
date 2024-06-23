<?php
// FROM HASH: 8250bbc8b6f27b6161de88e6a0bb426f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' tagged you in media ' . (((('<a href="' . $__templater->func('link', array('media', $__vars['content']['MediaItem'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['MediaItem']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);