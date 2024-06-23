<?php
// FROM HASH: e95694dbf7a99667019c7aa1fe405ce1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['content']['thumbnail_date']) {
		$__compilerTemp1 .= '
		';
		$__templater->includeCss('xfmg_album_view.less');
		$__compilerTemp1 .= '
		<div class="album-container">
			<a href="' . $__templater->func('link', array('media/albums', $__vars['content'], ), true) . '">
				<img src="' . $__templater->escape($__vars['content']['thumbnail_url']) . '" loading="lazy" />
			</a>
		</div>
	';
	}
	$__compilerTemp2 = '';
	if ($__vars['content']['description']) {
		$__compilerTemp2 .= '
		<div>' . $__templater->func('structured_text', array($__vars['content']['description'], ), true) . '</div>
	';
	}
	$__vars['messageHtml'] = $__templater->preEscaped('
	<h4 class="message-title"><a href="' . $__templater->func('link', array('media/albums', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h4>
	' . $__compilerTemp1 . '
	' . $__compilerTemp2 . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['create_date'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Album',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => '',
	), $__vars);
	return $__finalCompiled;
}
);