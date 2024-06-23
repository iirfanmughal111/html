<?php
// FROM HASH: aa3be48224a907cb13a5762e877f52c2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in a reply to a review on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/review-reply', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['ItemRating']['Item']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);