<?php
// FROM HASH: 20ef66be5811486387a2ca2eb058b2fa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
    ' . 'New post was created in the group ' . $__templater->escape($__vars['group']['name']) . '' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['post']['User'], $__vars['post']['username'], ), true) . ' posted a new post to a group you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:group-posts', $__vars['post'], ), true) . '">' . $__templater->escape($__vars['group']['name']) . '</a></h2>

<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['post']['FirstComment']['message'], 'tl_group_comment', $__vars['post']['FirstComment'], ), true) . '</div>

<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar">
    <tr>
        <td>
            <a href="' . $__templater->func('link', array('canonical:group-posts', $__vars['post'], ), true) . '" class="button">' . 'View this post' . '</a>
        </td>
    </tr>
</table>

' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the group ' . (((('<a href="' . $__templater->func('link', array('canonical:groups', $__vars['group'], ), true)) . '">') . $__templater->escape($__vars['group']['name'])) . '</a>') . ' at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new posts.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'tl_group_post', 'id' => $__vars['post']['post_id'], ), ), true) . '">disable emails from this thread</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>';
	return $__finalCompiled;
}
);