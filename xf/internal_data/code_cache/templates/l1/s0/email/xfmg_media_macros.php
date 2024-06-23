<?php
// FROM HASH: 249f8f9fb29859f5707a0b2d0eef1f71
return array(
'macros' => array('go_media_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'watchType' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar">
	<tr>
		<td>
			<a href="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), true) . '" class="button">' . 'View this media item' . '</a>
		</td>
		<td align="' . ($__vars['xf']['isRtl'] ? 'left' : 'right') . '">
			';
	if ($__vars['watchType'] == 'category') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/media-categories', ), true) . '" class="buttonFake">' . 'Watched categories' . '</a>
			';
	} else if ($__vars['watchType'] == 'media') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/media', ), true) . '" class="buttonFake">' . 'Watched media' . '</a>
			';
	}
	$__finalCompiled .= '
		</td>
	</tr>
	</table>
';
	return $__finalCompiled;
}
),
'watched_category_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the category "' . $__templater->escape($__vars['category']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new media items.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'xfmg_category', 'id' => $__vars['category']['category_id'], ), ), true) . '">disable emails from this category</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
),
'watched_media_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the media item "' . $__templater->escape($__vars['mediaItem']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new comments.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'xfmg_media', 'id' => $__vars['mediaItem']['media_id'], ), ), true) . '">disable emails from this media item</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);