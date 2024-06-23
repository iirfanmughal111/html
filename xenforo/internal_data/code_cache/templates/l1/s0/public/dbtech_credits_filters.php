<?php
// FROM HASH: 32e5f1c423dbc8ba45cf58954b118349
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['currencyFilter']);
	$__compilerTemp2 = array(array(
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->mergeChoiceOptions($__compilerTemp2, $__vars['eventFilter']);
	$__compilerTemp3 = array(array(
		'_type' => 'option',
	));
	$__compilerTemp3 = $__templater->mergeChoiceOptions($__compilerTemp3, $__vars['eventTriggerFilter']);
	$__finalCompiled .= $__templater->form('
	<!--[Credits:above_target]-->
	<div class="menu-row menu-row--separated">
		' . 'Target User' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'target',
		'value' => ($__vars['targetFilter'] ? $__vars['targetFilter']['username'] : ''),
		'ac' => 'single',
	)) . '
		</div>
	</div>

	<!--[Credits:above_source]-->
	<div class="menu-row menu-row--separated">
		' . 'Source User' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'source',
		'value' => ($__vars['sourceFilter'] ? $__vars['sourceFilter']['username'] : ''),
		'ac' => 'single',
	)) . '
		</div>
	</div>

	<!--[Credits:above_currency]-->
	<div class="menu-row menu-row--separated">
		' . 'Currency' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'currency_id',
		'value' => ($__vars['filters']['currency_id'] ?: ''),
	), $__compilerTemp1) . '
		</div>
	</div>

	<!--[Credits:above_event]-->
	<div class="menu-row menu-row--separated">
		' . 'Event' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'event_id',
		'value' => ($__vars['filters']['event_id'] ?: ''),
	), $__compilerTemp2) . '
		</div>
	</div>

	<!--[Credits:above_event_trigger]-->
	<div class="menu-row menu-row--separated">
		' . 'Event Trigger' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'event_trigger_id',
		'value' => ($__vars['filters']['event_trigger_id'] ?: ''),
	), $__compilerTemp3) . '
		</div>
	</div>

	<!--[Credits:above_sort_by]-->
	<div class="menu-row menu-row--separated">
		' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
		<div class="inputGroup u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'order',
		'value' => ($__vars['filters']['order'] ?: 'dateline'),
	), array(array(
		'value' => 'dateline',
		'label' => 'Date',
		'_type' => 'option',
	),
	array(
		'value' => 'amount',
		'label' => 'Amount',
		'_type' => 'option',
	))) . '
			<span class="inputGroup-splitter"></span>
			' . $__templater->formSelect(array(
		'name' => 'direction',
		'value' => ($__vars['filters']['direction'] ?: 'desc'),
	), array(array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	),
	array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	))) . '
		</div>
	</div>

	<div class="menu-footer">
		<span class="menu-footer-controls">
			' . $__templater->button('Filter', array(
		'type' => 'submit',
		'class' => 'button--primary',
	), '', array(
	)) . '
		</span>
	</div>
	' . $__templater->formHiddenVal('apply', '1', array(
	)) . '
', array(
		'action' => $__templater->func('link', array('dbtech-credits', '', array('action' => 'filters', ), ), false),
	));
	return $__finalCompiled;
}
);