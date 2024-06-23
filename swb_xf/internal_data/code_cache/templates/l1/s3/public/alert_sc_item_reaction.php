<?php
// FROM HASH: b5a57ff55cd6720550541b3822ce847e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your item ' . (((('<a href="' . $__templater->func('link', array('showcase', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['extra']['reaction_id'], ), false), array(array('preescaped', array()),), true) . '.';
	return $__finalCompiled;
}
);