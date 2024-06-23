<?php
// FROM HASH: 70813cbb2b9d23c4fd42ef30df6e43ad
return array(
'macros' => array('reaction_snippet' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reactionUser' => '!',
		'reactionId' => '!',
		'album' => '!',
		'date' => '!',
		'fallbackName' => 'Unknown member',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow-title">
		';
	if ($__vars['album']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to your album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['album'], ), true)) . '">') . $__templater->escape($__vars['album']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
		';
	} else {
		$__finalCompiled .= '
			' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to ' . $__templater->escape($__vars['album']['username']) . '\'s album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['album'], ), true)) . '">') . $__templater->escape($__vars['album']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
		';
	}
	$__finalCompiled .= '
	</div>

	<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['album']['description'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], ), true) . '</div>

	';
	if ($__vars['album']['thumbnail_date']) {
		$__finalCompiled .= '
		<div class="contentRow-figure contentRow-figure--fixedMedium"><a href="' . $__templater->func('link', array('media/albums', $__vars['album'], ), true) . '"><img src="' . $__templater->escape($__vars['album']['thumbnail_url']) . '" loading="lazy" alt="' . $__templater->escape($__vars['album']['title']) . '" /></a></div>
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
		'album' => $__vars['content'],
		'date' => $__vars['reaction']['reaction_date'],
	), $__vars);
	return $__finalCompiled;
}
);