<?php
// FROM HASH: 53c4010ad7e356b40f5fd7969b36aacf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' intends to maybe participate in your event ' . (((('<a href="' . $__templater->func('link', array('group-events', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['event_name'])) . '</a>') . '.';
	return $__finalCompiled;
}
);