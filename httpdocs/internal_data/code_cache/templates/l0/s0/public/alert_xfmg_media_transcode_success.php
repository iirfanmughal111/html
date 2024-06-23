<?php
// FROM HASH: d3fc1341f717db3d615a093bea7bea8c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your media item ' . (((('<a href="' . $__templater->func('link', array('media', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . ' has finished processing and has now been added to the gallery.';
	return $__finalCompiled;
}
);