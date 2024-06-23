<?php
// FROM HASH: 82f65ff12c28b4940311b7424bee64b7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
    ' . 'New event ' . $__templater->escape($__vars['event']['event_name']) . ' was created in the group ' . $__templater->escape($__vars['group']['name']) . '' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['event']['User'], $__vars['event']['username'], ), true) . ' posted a new event to a group you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:group-events', $__vars['event'], ), true) . '">' . $__templater->escape($__vars['event']['event_name']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
    <div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['event']['FirstComment']['message'], 'tl_group_comment', $__vars['event']['FirstComment'], ), true) . '</div>
';
	}
	$__finalCompiled .= '

<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar">
    <tr>
        <td>
            <a href="' . $__templater->func('link', array('canonical:group-events', $__vars['event'], ), true) . '" class="button">' . 'View this event' . '</a>
        </td>
    </tr>
</table>

' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the group ' . (((('<a href="' . $__templater->func('link', array('canonical:groups', $__vars['group'], ), true)) . '">') . $__templater->escape($__vars['group']['name'])) . '</a>') . ' at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new events.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'tl_group_event', 'id' => $__vars['event']['event_id'], ), ), true) . '">disable emails from this thread</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>';
	return $__finalCompiled;
}
);