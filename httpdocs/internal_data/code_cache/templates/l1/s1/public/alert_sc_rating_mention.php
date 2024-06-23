<?php
// FROM HASH: 5b0ead0019fff8cdfb387db1892b9649
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in a review on item ' . (((('<a href="' . $__templater->func('link', array('showcase/review', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);