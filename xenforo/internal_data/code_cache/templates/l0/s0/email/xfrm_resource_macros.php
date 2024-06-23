<?php
// FROM HASH: 1aeeffdc495cb06c1e62ecdd836cb90d
return array(
'macros' => array('go_resource_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
		'watchType' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar">
	<tr>
		<td>
			<a href="' . $__templater->func('link', array('canonical:resources', $__vars['resource'], ), true) . '" class="button">' . 'View this resource' . '</a>
		</td>
		<td align="' . ($__vars['xf']['isRtl'] ? 'left' : 'right') . '">
			';
	if ($__vars['watchType'] == 'resource') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/resources', ), true) . '" class="buttonFake">' . 'Watched resources' . '</a>
			';
	} else if ($__vars['watchType'] == 'category') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/resource-categories', ), true) . '" class="buttonFake">' . 'Watched categories' . '</a>
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
		'resource' => '!',
		'category' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the category "' . $__templater->escape($__vars['category']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new resources or updates.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'resource_category', 'id' => $__vars['category']['resource_category_id'], ), ), true) . '">disable emails from this category</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
),
'watched_resource_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the resource "' . $__templater->escape($__vars['resource']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of updates.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'resource', 'id' => $__vars['resource']['resource_id'], ), ), true) . '">disable emails from this resource</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
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