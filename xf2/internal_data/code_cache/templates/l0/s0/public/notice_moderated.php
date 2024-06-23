<?php
// FROM HASH: 298826cc0575e4ba63c7cc6df11fa323
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['xf']['visitor']['account_type'] == 2) {
		$__finalCompiled .= '
	' . 'Check your email for the documents
needed for verification. Until we get these documents, your account won\'t be approved
and is waiting for admin review.' . '
	';
	} else {
		$__finalCompiled .= '
	' . 'Your account is currently awaiting approval by an administrator. You will receive an email when a decision has been taken.' . '
';
	}
	$__finalCompiled .= '
';
	if ($__vars['xf']['session']['hasPreRegActionPending']) {
		$__finalCompiled .= '
	' . 'Once your registration has been completed, your content will be posted automatically.' . '
';
	}
	return $__finalCompiled;
}
);