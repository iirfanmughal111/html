<?php
// FROM HASH: 80383c9ff4357f15e22bc767749228ea
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' also replied to a review of the item ' . ((((('<a href="' . $__templater->func('link', array('showcase/review-reply', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content']['ItemRating']['Item'], ), true)) . $__templater->escape($__vars['content']['ItemRating']['Item']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);