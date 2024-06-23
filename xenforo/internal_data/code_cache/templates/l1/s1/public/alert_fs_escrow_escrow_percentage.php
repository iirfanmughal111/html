<?php
// FROM HASH: b13e8e33feae6c463e769190a151f17c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['username'], ), ), true) . ' received percentge a escrow named ' . (((('<a href="' . $__templater->func('link', array('threads/' . $__vars['extra']['thread_id'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['thread_title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);