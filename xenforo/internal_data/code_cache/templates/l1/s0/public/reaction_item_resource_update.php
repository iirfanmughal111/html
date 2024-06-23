<?php
// FROM HASH: 416d259ec86f4d77d656ca1c0f3a3a4e
return array(
'macros' => array('reaction_snippet' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reactionUser' => '!',
		'reactionId' => '!',
		'update' => '!',
		'date' => '!',
		'fallbackName' => 'Unknown member',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow-title">
		';
	if ($__vars['update']['Resource']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
			';
		if ($__templater->method($__vars['update'], 'isDescription', array())) {
			$__finalCompiled .= '
				' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to your resource ' . ((((('<a href="' . $__templater->func('link', array('resources', $__vars['update']['Resource'], ), true)) . '">') . $__templater->func('prefix', array('resource', $__vars['update']['Resource'], ), true)) . $__templater->escape($__vars['update']['Resource']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
			';
		} else {
			$__finalCompiled .= '
				' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to your update for resource ' . ((((('<a href="' . $__templater->func('link', array('resources/update', $__vars['update'], ), true)) . '">') . $__templater->func('prefix', array('resource', $__vars['update']['Resource'], ), true)) . $__templater->escape($__vars['update']['Resource']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
			';
		}
		$__finalCompiled .= '
		';
	} else {
		$__finalCompiled .= '
			';
		if ($__templater->method($__vars['update'], 'isDescription', array())) {
			$__finalCompiled .= '
				' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to ' . $__templater->escape($__vars['update']['Resource']['username']) . '\'s resource ' . ((((('<a href="' . $__templater->func('link', array('resources', $__vars['update']['Resource'], ), true)) . '">') . $__templater->func('prefix', array('resource', $__vars['update']['Resource'], ), true)) . $__templater->escape($__vars['update']['Resource']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
			';
		} else {
			$__finalCompiled .= '
				' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to ' . $__templater->escape($__vars['update']['Resource']['username']) . '\'s update for resource ' . ((((('<a href="' . $__templater->func('link', array('resources/update', $__vars['update'], ), true)) . '">') . $__templater->func('prefix', array('resource', $__vars['update']['Resource'], ), true)) . $__templater->escape($__vars['update']['Resource']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
			';
		}
		$__finalCompiled .= '
		';
	}
	$__finalCompiled .= '
	</div>

	<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['update']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>

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
		'update' => $__vars['content'],
		'date' => $__vars['reaction']['reaction_date'],
	), $__vars);
	return $__finalCompiled;
}
);