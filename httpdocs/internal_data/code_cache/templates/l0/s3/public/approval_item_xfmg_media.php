<?php
// FROM HASH: 6ce8cb7ae5d7b3b96f6239c903cfeaff
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['content']['description']) {
		$__compilerTemp1 .= '
		<div>' . $__templater->func('structured_text', array($__vars['content']['description'], ), true) . '</div>
	';
	}
	$__vars['messageHtml'] = $__templater->preEscaped('
	<div class="media-container">
		' . $__templater->callMacro('xfmg_media_view_macros', 'media_content', array(
		'mediaItem' => $__vars['content'],
	), $__vars) . '
	</div>

	' . $__compilerTemp1 . '
');
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__vars['content']['album_id']) {
		$__compilerTemp2 .= '
		' . '<a href="' . $__templater->func('link', array('media', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a> posted in album <a href="' . $__templater->func('link', array('media/albums', $__vars['content']['Album'], ), true) . '">' . $__templater->escape($__vars['content']['Album']['title']) . '</a>' . '
	';
	} else {
		$__compilerTemp2 .= '
		' . '<a href="' . $__templater->func('link', array('media', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a> posted in category <a href="' . $__templater->func('link', array('media/categories', $__vars['content']['Category'], ), true) . '">' . $__templater->escape($__vars['content']['Category']['title']) . '</a>' . '
	';
	}
	$__vars['headerPhraseHtml'] = $__templater->preEscaped('
	' . $__compilerTemp2 . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['media_date'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Media item',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => $__vars['headerPhraseHtml'],
	), $__vars);
	return $__finalCompiled;
}
);