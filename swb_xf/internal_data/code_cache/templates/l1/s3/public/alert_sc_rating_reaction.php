<?php
// FROM HASH: 5691da37a9a517e347c5e99fc0bbded3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your review on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/review', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['extra']['reaction_id'], ), false), array(array('preescaped', array()),), true) . '.';
	return $__finalCompiled;
}
);