<?php
// FROM HASH: b1189cfaf34da83cbfd6c178cd4b59a6
return array(
'macros' => array('go_item_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'watchType' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar">
	<tr>
		<td>
			<a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), true) . '" class="button">' . 'View this item' . '</a>
		</td>
		<td align="' . ($__vars['xf']['isRtl'] ? 'left' : 'right') . '">
			';
	if ($__vars['watchType'] == 'category') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/showcase-categories', ), true) . '" class="buttonFake">' . 'Watched categories' . '</a>
			';
	} else if ($__vars['watchType'] == 'series') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/showcase-series', ), true) . '" class="buttonFake">' . 'Watched series' . '</a>				
			';
	} else if ($__vars['watchType'] == 'item') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/showcase-items', ), true) . '" class="buttonFake">' . 'Watched items' . '</a>
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

<p class="minorText">This message was sent to you because you opted to watch the category "' . $__templater->escape($__vars['category']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new showcase items.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'sc_category', 'id' => $__vars['category']['category_id'], ), ), true) . '">disable emails from this category</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
),
'watched_series_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the series "' . $__templater->escape($__vars['series']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new items.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'sc_series', 'id' => $__vars['series']['series_id'], ), ), true) . '">disable emails from this series</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
),
'watched_item_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the showcase item "' . $__templater->escape($__vars['item']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of item updates, new comments and new reviews.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'sc_item', 'id' => $__vars['item']['item_id'], ), ), true) . '">disable emails from this item</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);