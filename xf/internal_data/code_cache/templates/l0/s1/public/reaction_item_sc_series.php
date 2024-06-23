<?php
// FROM HASH: 19ec3b290e4a98a9e432a8c3c3640601
return array(
'macros' => array('reaction_snippet' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reactionUser' => '!',
		'reactionId' => '!',
		'seriesItem' => '!',
		'date' => '!',
		'fallbackName' => 'Unknown member',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="contentRow-title">
		';
	if ($__vars['seriesItem']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to your series ' . (((('<a href="' . $__templater->func('link', array('showcase/series/details', $__vars['seriesItem'], ), true)) . '">') . $__templater->escape($__vars['seriesItem']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
		';
	} else {
		$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to ' . $__templater->escape($__vars['seriesItem']['username']) . '\'s series ' . (((('<a href="' . $__templater->func('link', array('showcase/series/details', $__vars['seriesItem'], ), true)) . '">') . $__templater->escape($__vars['seriesItem']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
		';
	}
	$__finalCompiled .= '
	</div>

	<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['seriesItem']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>

	';
	if ($__vars['seriesItem']['icon_date']) {
		$__finalCompiled .= '
		<div class="contentRow-figure contentRow-figure--fixedMedium"><a href="' . $__templater->func('link', array('showcase/series/details', $__vars['seriesItem'], ), true) . '">' . $__templater->func('sc_series_icon', array($__vars['seriesItem'], 'm', ), true) . '</a></div>
	';
	}
	$__finalCompiled .= '

	<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['date'], array(
	))) . '</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->callMacro(null, 'reaction_snippet', array(
		'reactionUser' => $__vars['reaction']['ReactionUser'],
		'reactionId' => $__vars['reaction']['reaction_id'],
		'seriesItem' => $__vars['content'],
		'date' => $__vars['reaction']['reaction_date'],
	), $__vars);
	return $__finalCompiled;
}
);