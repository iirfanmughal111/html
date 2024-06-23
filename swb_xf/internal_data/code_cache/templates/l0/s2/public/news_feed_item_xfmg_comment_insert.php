<?php
// FROM HASH: c6b1c62a69f298f4da4c9401873b8b93
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	';
	if ($__vars['content']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' posted a comment on the media item ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['Media']['title'])) . '</a>') . '.' . '
	';
	} else {
		$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' posted a comment on the album ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['Album']['title'])) . '</a>') . '.' . '
	';
	}
	$__finalCompiled .= '
</div>

<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['content']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);