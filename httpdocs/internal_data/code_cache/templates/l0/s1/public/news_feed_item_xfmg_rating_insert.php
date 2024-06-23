<?php
// FROM HASH: 3b82b13d38141f79c7df18c89ff1415c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	';
	if ($__vars['content']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
		';
		if ($__vars['content']['Comment'] AND $__templater->method($__vars['content']['Comment'], 'canView', array())) {
			$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' reviewed the media item ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content']['Comment'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Media']['title'])) . '</a>') . '.' . '
		';
		} else {
			$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' rated the media item ' . (((('<a href="' . $__templater->func('link', array('media', $__vars['content']['Media'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Media']['title'])) . '</a>') . '.' . '
		';
		}
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
		';
		if ($__vars['content']['Comment'] AND $__templater->method($__vars['content']['Comment'], 'canView', array())) {
			$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' reviewed the album ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content']['Comment'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Album']['title'])) . '</a>') . '.' . '
		';
		} else {
			$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' rated the album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['content']['Album'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Album']['title'])) . '</a>') . '.' . '
		';
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
</div>

<div class="contentRow-snippet">
	' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['content']['rating'],
		'class' => 'ratingStars--smaller',
	), $__vars) . '

	';
	if ($__vars['content']['Comment'] AND $__templater->method($__vars['content']['Comment'], 'canView', array())) {
		$__finalCompiled .= '
		' . $__templater->func('snippet', array($__vars['content']['Comment']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '
	';
	}
	$__finalCompiled .= '
</div>

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);