<?php
// FROM HASH: 07ef5856cced481eb92f623ebdffa4dc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' left the item contributors team for the item ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content'], ), true)) . $__templater->escape($__vars['content']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);