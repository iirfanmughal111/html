<?php
// FROM HASH: 454260a1b2b2955bb36ad1f117293537
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' replied to a reivew on your item ' . ((((('<a href="' . $__templater->func('link', array('showcase/review-reply', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content']['ItemRating']['Item'], ), true)) . $__templater->escape($__vars['content']['ItemRating']['Item']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);