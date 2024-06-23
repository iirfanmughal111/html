<?php
// FROM HASH: 4ec8ea9f028fb0ec5eb4862d2be64b8d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['requestLink'] = $__templater->preEscaped($__templater->func('link', array('escrow/withdraw', ), true));
	$__finalCompiled .= '

<mail:subject>
	' . 'Withdraw Request OTP' . '
</mail:subject>

	' . '<br> 
Hello <strong>' . $__templater->escape($__vars['visitor']['username']) . '</strong>,
Please use the following OTP to submit withdrawal request, 
<br><br>
' . $__templater->escape($__vars['visitor']['escrow_otp']) . '
<br><br>
Thank you...!
<br><br>' . '
<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar" >
	<tr>
		<td >
			<a href="' . $__templater->escape($__vars['requestLink']) . '" class="button">' . 'Visit' . '</a>
		</td>
	</tr>
</table>';
	return $__finalCompiled;
}
);