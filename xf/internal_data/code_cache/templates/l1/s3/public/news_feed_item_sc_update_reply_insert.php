<?php
// FROM HASH: bfd7bc3f5e195a73b94a4ce58b5279b9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' posted a reply to the update ' . (((('<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['ItemUpdate']['title'])) . '</a>') . ' on the item ' . ((((('<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['content']['ItemUpdate']['Item'], ), true)) . $__templater->escape($__vars['content']['ItemUpdate']['Item']['title'])) . '</a>') . '.' . '
</div>

<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['content']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);