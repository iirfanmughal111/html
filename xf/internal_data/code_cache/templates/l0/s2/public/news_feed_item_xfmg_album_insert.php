<?php
// FROM HASH: 9ce3e9a819538eea3000a958f5bcb497
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	';
	if ($__vars['content']['category_id']) {
		$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' created the album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['title'])) . '</a>') . ' in ' . (((('<a href="' . $__templater->func('link', array('media/categories', $__vars['content']['Category'], ), true)) . '">') . $__templater->escape($__vars['content']['Category']['title'])) . '</a>') . '.' . '
	';
	} else {
		$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' created the album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['title'])) . '</a>') . '.' . '
	';
	}
	$__finalCompiled .= '
</div>

';
	if ($__vars['content']['description']) {
		$__finalCompiled .= '
	<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['content']['description'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['content']['thumbnail_date']) {
		$__finalCompiled .= '
	<div class="contentRow-figure contentRow-figure--fixedMedium"><a href="' . $__templater->func('link', array('media/albums', $__vars['content'], ), true) . '"><img src="' . $__templater->escape($__vars['content']['thumbnail_url']) . '" loading="lazy" alt="' . $__templater->escape($__vars['content']['title']) . '" /></a></div>
';
	}
	$__finalCompiled .= '

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);