<?php
// FROM HASH: 44df14a32064d7f89f84eb8d3ece3c03
return array(
'macros' => array('go_album_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar">
	<tr>
		<td>
			<a href="' . $__templater->func('link', array('canonical:media/albums', $__vars['album'], ), true) . '" class="button">' . 'View this album' . '</a>
		</td>
		<td align="' . ($__vars['xf']['isRtl'] ? 'left' : 'right') . '">
			<a href="' . $__templater->func('link', array('canonical:watched/media-albums', ), true) . '" class="buttonFake">' . 'Watched albums' . '</a>
		</td>
	</tr>
	</table>
';
	return $__finalCompiled;
}
),
'watched_album_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the media item "' . $__templater->escape($__vars['album']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new media items or new comments.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'xfmg_album', 'id' => $__vars['album']['album_id'], ), ), true) . '">disable emails from this album</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);