<?php
// FROM HASH: 1534082759ade0f0d4c0a03991547efe
return array(
'macros' => array('reaction_snippet' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reactionUser' => '!',
		'reactionId' => '!',
		'page' => '!',
		'date' => '!',
		'fallbackName' => 'Unknown member',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="contentRow-title">
		';
	if ($__vars['page']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to your page ' . (((('<a href="' . $__templater->func('link', array('showcase/page', $__vars['page'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['page']['title'])) . '</a>') . ' on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/page', $__vars['page'], ), true)) . '">') . $__templater->escape($__vars['page']['Content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
		';
	} else {
		$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to ' . $__templater->escape($__vars['page']['username']) . '\'s page ' . (((('<a href="' . $__templater->func('link', array('showcase/page', $__vars['page'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['page']['title'])) . '</a>') . ' on the item ' . (((('<a href="' . $__templater->func('link', array('showcase/page', $__vars['page'], ), true)) . '">') . $__templater->escape($__vars['page']['Content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
		';
	}
	$__finalCompiled .= '
	</div>

	<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['page']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>

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
		'page' => $__vars['content'],
		'date' => $__vars['reaction']['reaction_date'],
	), $__vars);
	return $__finalCompiled;
}
);