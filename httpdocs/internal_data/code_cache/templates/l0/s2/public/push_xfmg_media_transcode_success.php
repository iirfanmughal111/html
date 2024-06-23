<?php
// FROM HASH: e993339d77cd3ad2627539d98f1f35f1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your media item ' . $__templater->escape($__vars['content']['title']) . ' has finished processing and has now been added to the gallery.' . '
<push:url>' . $__templater->func('link', array('canonical:media', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);