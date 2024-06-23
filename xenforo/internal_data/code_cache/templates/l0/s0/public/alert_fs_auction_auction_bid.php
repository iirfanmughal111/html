<?php
// FROM HASH: 35028899ada8ded72a9de1c5d8f17f71
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['auctionLink'] = $__templater->preEscaped($__templater->func('link', array(((('auction/' . $__vars['extra']['category_id']) . '/') . $__vars['alert']['content_id']) . '/view-auction', ), true));
	$__finalCompiled .= '
' . 'Your auction <a href="' . $__templater->escape($__vars['auctionLink']) . '"> ' . $__templater->escape($__vars['extra']['title']) . '</a> get new bid from user: ' . $__templater->func('username_link', array($__vars['alert']['User'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . '.';
	return $__finalCompiled;
}
);