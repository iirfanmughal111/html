<?php
// FROM HASH: 5edc997fb1ce98b3e2eafc8283f2f866
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your reply to the update ' . (((('<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['ItemUpdate']['title'])) . '</a>') . ' on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['ItemUpdate']['Item']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['extra']['reaction_id'], ), false), array(array('preescaped', array()),), true) . '.';
	return $__finalCompiled;
}
);