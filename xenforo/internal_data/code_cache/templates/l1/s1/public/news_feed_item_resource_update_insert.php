<?php
// FROM HASH: e96cb643408e240556777af2ec33a8f0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' updated the resource ' . ((((('<a href="' . $__templater->func('link', array('resources/update', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('resource', $__vars['content']['Resource'], ), true)) . $__templater->escape($__vars['content']['Resource']['title'])) . '</a>') . ' with ' . (((('<a href="' . $__templater->func('link', array('resources/update', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['title'])) . '</a>') . '.' . '
</div>

<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['content']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>
';
	if ($__vars['content']['attach_count']) {
		$__finalCompiled .= '
	' . $__templater->callMacro('news_feed_attached_images', 'attached_images', array(
			'attachments' => $__vars['content']['Attachments'],
			'link' => $__templater->func('link', array('resources', $__vars['content'], ), false),
		), $__vars) . '
';
	}
	$__finalCompiled .= '

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);