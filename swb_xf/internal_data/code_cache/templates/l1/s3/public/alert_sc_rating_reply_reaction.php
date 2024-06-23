<?php
// FROM HASH: 492b4b6bb4a23d4a1a6440e5f9a71a60
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your reply to a review on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/review-reply', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['ItemRating']['Item']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['extra']['reaction_id'], ), false), array(array('preescaped', array()),), true) . '.';
	return $__finalCompiled;
}
);