<?php
// FROM HASH: 3f881fec53b696273d0368ac53413888
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => 'Anonymous', ), ), true) . ' reviewed the item ' . ((((('<a href="' . $__templater->func('link', array('showcase/review', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . '.' . '
</div>

<div class="contentRow-snippet">
	' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['content']['rating'],
		'class' => 'ratingStars--smaller',
	), $__vars) . '

	' . $__templater->func('snippet', array($__vars['content']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '
</div>

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);