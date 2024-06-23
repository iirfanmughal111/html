<?php
// FROM HASH: 424fc354de5f25dcfe7870ff5dc15935
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reviewed the resource ' . ((((('<a href="' . $__templater->func('link', array('resources/review', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('resource', $__vars['content']['Resource'], ), true)) . $__templater->escape($__vars['content']['Resource']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);