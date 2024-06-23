<?php
// FROM HASH: 4ccab950ec46885035bc3ea5fa4774fd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'Action Required to Activate Membership for SouthWestBoard' . '
</mail:subject>

' . 'Dear ' . $__templater->escape($__vars['user']['username']) . '<br><br>

Thank you for registering at the SouthWestBoard. Before we can activate your account one last stepmust be taken to complete your registration <br>

Please note-you must complete this last step to become a registered member.You will only need to visit this URL once to activate your account
<br><br>
To complete your registration, please visit this URL:<br><br>
' . $__templater->escape($__vars['link']) . '

<br><br>
***** Does The Above URL Not Work? *****
<br><br>

' . $__templater->escape($__vars['direct_link']) . '
<br><br>

please be sure not to add extra spaces. You will need to type in your username and activation number on the page that appears when you visit the URL
<br><br>
You Username : ' . $__templater->escape($__vars['user']['username']) . ' <br>
Your Activation Id : ' . $__templater->escape($__vars['activation_id']) . '
<br><br>
If you are are still having problems signing up  please contact a member of our support statt at 

<br><br>

' . $__templater->escape($__vars['xf']['options']['contactEmailAddress']) . '


';
	return $__finalCompiled;
}
);