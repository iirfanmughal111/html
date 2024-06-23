<?php
// FROM HASH: 7a8a6736276c9d634e26d35d6b2847c2
return array(
'macros' => array('reaction_snippet' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reactionUser' => '!',
		'reactionId' => '!',
		'item' => '!',
		'date' => '!',
		'fallbackName' => 'Unknown member',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow-title">
		';
	if ($__vars['item']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to your item ' . (((('<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true)) . '">') . $__templater->escape($__vars['item']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
		';
	} else {
		$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to ' . $__templater->escape($__vars['item']['username']) . '\'s item ' . (((('<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true)) . '">') . $__templater->escape($__vars['item']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
		';
	}
	$__finalCompiled .= '
	</div>

	<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['item']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>

	';
	if ($__vars['item']['CoverImage']) {
		$__finalCompiled .= '
		<div class="contentRow-figure contentRow-figure--fixedMedium"><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . ' </a></div>
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
		'item' => $__vars['content'],
		'date' => $__vars['reaction']['reaction_date'],
	), $__vars);
	return $__finalCompiled;
}
);