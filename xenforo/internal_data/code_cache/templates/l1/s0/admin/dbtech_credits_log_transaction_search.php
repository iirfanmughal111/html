<?php
// FROM HASH: a35f3189d0f0fe54372ec39c3353dade
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search transactions');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formNumberBoxRow(array(
		'name' => 'criteria[transaction_id]',
		'value' => $__vars['criteria']['transaction_id'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Transaction ID',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[SourceUser][username]',
		'ac' => 'single',
		'value' => $__vars['criteria']['SourceUser']['username'],
	), array(
		'label' => 'Source User',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[TargetUser][username]',
		'ac' => 'single',
		'value' => $__vars['criteria']['TargetUser']['username'],
	), array(
		'label' => 'Target User',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[dateline][start]',
		'value' => $__vars['criteria']['dateline']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[dateline][end]',
		'value' => $__vars['criteria']['dateline']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Date range',
	)) . '

			' . $__templater->callMacro('public:dbtech_credits_event_macros', 'event_trigger_select', array(
		'inputName' => 'criteria[event_trigger_id]',
		'eventTriggerId' => '',
		'includeBlank' => false,
		'includeAny' => true,
	), $__vars) . '

			' . $__templater->callMacro('public:dbtech_credits_event_macros', 'event_select', array(
		'inputName' => 'criteria[event_id]',
		'eventId' => '',
		'includeBlank' => false,
		'includeAny' => true,
	), $__vars) . '

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[transaction_state]',
		'readonly' => $__vars['readOnly'],
	), array(array(
		'value' => 'visible',
		'selected' => $__templater->func('in_array', array('visible', $__vars['criteria']['transaction_state'], ), false),
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'selected' => $__templater->func('in_array', array('moderated', $__vars['criteria']['transaction_state'], ), false),
		'label' => 'Awaiting moderation',
		'_type' => 'option',
	),
	array(
		'value' => 'skipped',
		'selected' => $__templater->func('in_array', array('skipped', $__vars['criteria']['transaction_state'], ), false),
		'label' => 'Skipped',
		'_type' => 'option',
	),
	array(
		'value' => 'skipped_maximum',
		'selected' => $__templater->func('in_array', array('skipped_maximum', $__vars['criteria']['transaction_state'], ), false),
		'label' => 'Skipped (maximum applications)',
		'_type' => 'option',
	)), array(
		'label' => 'Transaction state',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('

				<div class="inputPair">
					' . $__templater->formSelect(array(
		'name' => 'order',
	), $__compilerTemp1) . '
					' . $__templater->formSelect(array(
		'name' => 'direction',
		'value' => 'desc',
	), array(array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	),
	array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	))) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Sort',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'search',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-credits/logs/transactions', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);