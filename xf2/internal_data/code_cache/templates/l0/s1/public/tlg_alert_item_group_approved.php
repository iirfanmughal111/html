<?php
// FROM HASH: dc202872316641ae7d8fde25f524125b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['name'])) . '</a>') . ' has been approved.';
	return $__finalCompiled;
}
);