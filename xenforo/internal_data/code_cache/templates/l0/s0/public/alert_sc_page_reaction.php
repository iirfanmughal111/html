<?php
// FROM HASH: 530d4e8349a8c5d7a0b0a7bfc661f6bf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your page ' . (((('<a href="' . $__templater->func('link', array('showcase/page', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . ' on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/page', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['extra']['reaction_id'], ), false), array(array('preescaped', array()),), true) . '.';
	return $__finalCompiled;
}
);