<?php
// FROM HASH: 0ee82761619f9e2d9dd69b77fd2aed3b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['username'], ), ), true) . ' cancel a escrow named ' . (((('<a href="' . $__templater->func('link', array('threads/' . $__vars['extra']['thread_id'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['thread_title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);