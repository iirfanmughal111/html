<?php
// FROM HASH: 00f7dcfc8b3b4dfa6ef469fc972f6922
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' added the item ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['content'], ), true)) . $__templater->escape($__vars['content']['title'])) . '</a>') . ' to ' . (((('<a href="' . $__templater->func('link', array('showcase/categories', $__vars['content']['Category'], ), true)) . '">') . $__templater->escape($__vars['content']['Category']['title'])) . '</a>') . '.' . '
</div>

<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['content']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>
';
	if ($__vars['content']['attach_count']) {
		$__finalCompiled .= '
	' . $__templater->callMacro('news_feed_attached_images', 'attached_images', array(
			'attachments' => $__vars['content']['Attachments'],
			'link' => $__templater->func('link', array('showcase', $__vars['content'], ), false),
		), $__vars) . '
';
	}
	$__finalCompiled .= '

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);