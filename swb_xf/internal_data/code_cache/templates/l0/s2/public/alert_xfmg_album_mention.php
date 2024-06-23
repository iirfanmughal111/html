<?php
// FROM HASH: dd893a3003352e969b035badf6f20a74
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you on album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);