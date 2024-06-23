<?php
// FROM HASH: 7eecec9aad10142a5ea45333596f67b2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('

	<div class="inputChoices">
		<div class="inputChoices-choice" style="padding-left:0;">
			<div class="inputGroup">
				' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[imapHost]',
		'value' => $__vars['option']['option_value']['imapHost'],
		'placeholder' => 'Host',
		'size' => '40',
	)) . '
				<span class="inputGroup-text">:</span>
				' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[imapPort]',
		'value' => $__vars['option']['option_value']['imapPort'],
		'placeholder' => 'Port',
		'size' => '5',
	)) . '
			</div>

			<div class="inputChoices-spacer" style="margin-bottom:6px;">' . 'Mailbox' . '</div>
			<div class="inputGroup">
				' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[imapMailbox]',
		'value' => $__vars['option']['option_value']['imapMailbox'],
		'placeholder' => 'Mailbox',
	)) . '
			</div>

			<div class="inputChoices-spacer">' . 'Authentication' . '</div>
			' . $__templater->formRadio(array(
		'name' => $__vars['inputName'] . '[imapAuth]',
		'value' => ($__vars['option']['option_value']['imapAuth'] ? $__vars['option']['option_value']['imapAuth'] : 'none'),
	), array(array(
		'value' => 'none',
		'label' => 'None',
		'_type' => 'option',
	),
	array(
		'value' => 'login',
		'label' => 'Username and password',
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[imapLoginUsername]',
		'value' => $__vars['option']['option_value']['imapLoginUsername'],
		'placeholder' => 'Username',
		'size' => '15',
	)) . '
							<span class="inputGroup-splitter"></span>
							' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[imapLoginPassword]',
		'value' => $__vars['option']['option_value']['imapLoginPassword'],
		'type' => 'password',
		'size' => '15',
	)) . '
						</div>
					'),
		'_type' => 'option',
	))) . '

			<div class="inputChoices-spacer">' . 'Encryption' . '</div>
			' . $__templater->formRadio(array(
		'name' => $__vars['inputName'] . '[imapEncrypt]',
		'value' => ($__vars['option']['option_value']['imapEncrypt'] ? $__vars['option']['option_value']['imapEncrypt'] : 'none'),
		'listclass' => 'indented',
	), array(array(
		'value' => 'none',
		'label' => 'None',
		'_type' => 'option',
	),
	array(
		'value' => 'tls',
		'label' => 'TLS',
		'_type' => 'option',
	),
	array(
		'value' => 'ssl',
		'label' => 'SSL',
		'_type' => 'option',
	))) . '
		</div>
	</div>
', array(
		'name' => $__vars['inputName'] . '[' . $__vars['option']['option_id'] . ']',
		'value' => $__vars['option']['option_value'][$__vars['option']['option_id']],
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);