<?php
// FROM HASH: faa4eb421ff27560065ddc107bf953f1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['event'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add event');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit event' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['event']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['event'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-credits/events/delete', $__vars['event'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__vars['eventTrigger'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\Credits:EventTrigger', )), 'getHandler', array($__vars['event']['event_trigger_id'], ));
	$__finalCompiled .= '
';
	$__vars['eventTriggerLabels'] = $__templater->method($__vars['eventTrigger'], 'getLabels', array());
	$__finalCompiled .= '
';
	$__vars['multiplier'] = $__templater->method($__vars['eventTrigger'], 'getMultiplier', array());
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['eventTrigger'], 'getOption', array('useUserGroups', ))) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('public:dbtech_credits_helper_user_group_edit', 'usable_checkboxes', array(
			'label' => 'Applicable user groups',
			'explain' => 'Only members of these user group(s) will have this event applied to them. If this event affects multiple participants, f.ex. a Donate event, both the source and target users\' groups must be selected in order for this event to function correctly.',
			'selectedUserGroups' => ($__vars['event']['event_id'] ? $__vars['event']['user_group_ids'] : array(-1, )),
		), $__vars) . '
			';
	}
	$__compilerTemp2 = '';
	if (!$__templater->method($__vars['eventTrigger'], 'getOption', array('isGlobal', ))) {
		$__compilerTemp2 .= '
				';
		$__compilerTemp3 = array(array(
			'value' => '-1',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'All' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		));
		$__compilerTemp4 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
		if ($__templater->isTraversable($__compilerTemp4)) {
			foreach ($__compilerTemp4 AS $__vars['treeEntry']) {
				$__compilerTemp3[] = array(
					'value' => $__vars['treeEntry']['record']['node_id'],
					'disabled' => (($__vars['treeEntry']['record']['node_type_id'] != 'Forum') ? true : false),
					'label' => $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp2 .= $__templater->formSelectRow(array(
			'name' => 'node_ids[]',
			'multiple' => 'true',
			'size' => '8',
			'value' => ($__vars['event']['event_id'] ? $__vars['event']['node_ids'] : array(-1, )),
		), $__compilerTemp3, array(
			'label' => 'Forums',
			'explain' => 'The specific forums this event can occur in. If none are selected then all forums will be applicable. Ctrl/Cmd+click or click+drag to select more than one.',
		)) . '
			';
	}
	$__compilerTemp5 = '';
	if ($__templater->method($__vars['eventTrigger'], 'getOption', array('useOwner', ))) {
		$__compilerTemp5 .= '
				' . $__templater->formRadioRow(array(
			'name' => 'owner',
			'value' => $__vars['event']['owner'],
		), array(array(
			'value' => '0',
			'label' => 'Always allowed for either',
			'_type' => 'option',
		),
		array(
			'value' => '1',
			'label' => $__templater->escape($__vars['eventTriggerLabels']['owner_only_others']),
			'_type' => 'option',
		),
		array(
			'value' => '2',
			'label' => $__templater->escape($__vars['eventTriggerLabels']['owner_only_own']),
			'_type' => 'option',
		)), array(
			'label' => 'Content ownership restriction',
			'explain' => $__templater->escape($__vars['eventTriggerLabels']['owner_explain']),
		)) . '
			';
	}
	$__compilerTemp6 = array();
	if ($__templater->method($__vars['eventTrigger'], 'getOption', array('canCharge', ))) {
		$__compilerTemp6[] = array(
			'name' => 'charge',
			'value' => '1',
			'selected' => $__vars['event']['charge'],
			'hint' => ((!$__templater->method($__vars['eventTrigger'], 'getOption', array('canCancel', ))) ? 'The calculated value of this event will either be awarded to the user, or deducted from them if this is enabled.<br />
When charging and the user does not have enough, the event trigger will NOT be stopped. Instead, this event will be skipped.' : 'The calculated value of this event will either be awarded to the user, or deducted from them if this is enabled.<br />
When charging and the user does not have enough, the event trigger will be stopped and an error message will be displayed to the user.'),
			'label' => '
						' . 'Is charged' . '
					',
			'_type' => 'option',
		);
	}
	$__compilerTemp6[] = array(
		'name' => 'moderate',
		'value' => '1',
		'selected' => $__vars['event']['moderate'],
		'hint' => 'If enabled, all transactions from this event will be sent to the Approval Queue.',
		'label' => '
					' . 'Transactions are moderated' . '
				',
		'_type' => 'option',
	);
	$__compilerTemp7 = '';
	if ($__vars['multiplier'] == 3) {
		$__compilerTemp7 .= '
				' . $__templater->formNumberBoxRow(array(
			'name' => 'main_add',
			'value' => $__vars['event']['main_add'],
			'step' => 'any',
		), array(
			'label' => 'Flat rate',
			'explain' => '<em>If not charging:</em> Extra amount added to the amount being transferred.<br />
<em>If charging:</em> Extra amount deducted from the amount being transferred.',
		)) . '
			';
	} else {
		$__compilerTemp7 .= '
				' . $__templater->formNumberBoxRow(array(
			'name' => 'main_add',
			'value' => $__vars['event']['main_add'],
			'step' => 'any',
		), array(
			'label' => 'Event amount',
			'explain' => '<em>If not charging:</em> Awarded when this event occurs.<br />
<em>If charging:</em> Charged when this event occurs.',
		)) . '

			';
	}
	$__compilerTemp8 = '';
	if ($__templater->method($__vars['eventTrigger'], 'getOption', array('canRevert', ))) {
		$__compilerTemp8 .= '
				';
		if ($__vars['multiplier'] == 3) {
			$__compilerTemp8 .= '
					' . $__templater->formNumberBoxRow(array(
				'name' => 'main_sub',
				'value' => $__vars['event']['main_sub'],
				'step' => 'any',
			), array(
				'label' => 'Flat rate negation',
				'explain' => '<em>If not charging:</em> Extra amount deducted from the amount being restored.<br />
<em>If charging:</em> Extra amount added to the amount being restored.',
			)) . '
				';
		} else {
			$__compilerTemp8 .= '
					' . $__templater->formNumberBoxRow(array(
				'name' => 'main_sub',
				'value' => $__vars['event']['main_sub'],
				'step' => 'any',
			), array(
				'label' => 'Event negation amount',
				'explain' => '<em>If not charging:</em> Removed when this event is reverted.<br />
<em>If charging:</em> Restored when this event is reverted.',
			)) . '

				';
		}
		$__compilerTemp8 .= '
			';
	}
	$__compilerTemp9 = '';
	if ($__vars['multiplier']) {
		$__compilerTemp9 .= '
				';
		if ($__vars['multiplier'] == 3) {
			$__compilerTemp9 .= '
					' . $__templater->formNumberBoxRow(array(
				'name' => 'mult_add',
				'value' => $__vars['event']['mult_add'],
				'step' => 'any',
			), array(
				'label' => 'Taxation',
				'explain' => 'Extra percentage in decimal form (if not charging) added to / (if charging) deducted from amount being transferred.',
			)) . '
				';
		} else {
			$__compilerTemp9 .= '
					' . $__templater->formNumberBoxRow(array(
				'name' => 'mult_add',
				'value' => $__vars['event']['mult_add'],
				'step' => 'any',
			), array(
				'label' => $__templater->escape($__vars['eventTriggerLabels']['multiplier_addition']),
				'explain' => $__templater->escape($__vars['eventTriggerLabels']['multiplier_addition_explain']),
			)) . '
				';
		}
		$__compilerTemp9 .= '

				';
		if ($__templater->method($__vars['eventTrigger'], 'getOption', array('canRevert', ))) {
			$__compilerTemp9 .= '
					';
			if ($__vars['multiplier'] == 3) {
				$__compilerTemp9 .= '
						' . $__templater->formNumberBoxRow(array(
					'name' => 'mult_sub',
					'value' => $__vars['event']['mult_sub'],
					'step' => 'any',
				), array(
					'label' => 'Taxation negation',
					'explain' => 'Extra percentage in decimal form (if not charging) deducted from / (if charging) added to amount being restored.',
				)) . '
					';
			} else {
				$__compilerTemp9 .= '
						' . $__templater->formNumberBoxRow(array(
					'name' => 'mult_sub',
					'value' => $__vars['event']['mult_sub'],
					'step' => 'any',
				), array(
					'label' => $__templater->escape($__vars['eventTriggerLabels']['multiplier_negation']),
					'explain' => $__templater->escape($__vars['eventTriggerLabels']['multiplier_negation_explain']),
				)) . '
					';
			}
			$__compilerTemp9 .= '
				';
		}
		$__compilerTemp9 .= '

				';
		if ($__vars['multiplier'] == 3) {
			$__compilerTemp9 .= '
					' . $__templater->formRadioRow(array(
				'name' => 'curtarget',
				'value' => $__vars['event']['curtarget'],
			), array(array(
				'value' => '0',
				'label' => 'Sending user',
				'_type' => 'option',
			),
			array(
				'value' => '1',
				'label' => 'Receiving user',
				'_type' => 'option',
			),
			array(
				'value' => '2',
				'label' => 'Both users',
				'_type' => 'option',
			)), array(
				'label' => 'Taxed user',
				'explain' => '<b>Sending user:</b> The original amount + the tax will be deducted from the sending user, and the receiving user will receive the original amount.<br />
<b>Receiving user:</b> The original amount will be deducted from the sending user, and the receiving user will receive the original amount minus the tax.<br />
<b>Both users:</b> The original amount + the tax will be deducted from the sending user, and the receiving user will receive the original amount minus the tax.',
			)) . '

					' . $__templater->formNumberBoxRow(array(
				'name' => 'multmin',
				'value' => $__vars['event']['multmin'],
				'step' => 'any',
			), array(
				'label' => 'Minimum currency amount',
				'explain' => '0 = no minimum.',
			)) . '

					' . $__templater->formNumberBoxRow(array(
				'name' => 'multmax',
				'value' => $__vars['event']['multmax'],
				'step' => 'any',
			), array(
				'label' => 'Maximum currency amount',
				'explain' => 'Amounts larger than this will be reduced to this amount before being used in "Amount per X" calculations.<br />
0 = no maximum.',
			)) . '

					' . $__templater->formRadioRow(array(
				'name' => 'minaction',
				'value' => $__vars['event']['minaction'],
			), array(array(
				'value' => '0',
				'label' => 'Ignore extra amount',
				'_type' => 'option',
			),
			array(
				'value' => '1',
				'label' => 'Skip this event',
				'_type' => 'option',
			),
			array(
				'value' => '2',
				'label' => ($__templater->method($__vars['eventTrigger'], 'getOption', array('canCancel', )) ? 'Display error message to the user' : 'Stop all events from this event trigger'),
				'_type' => 'option',
			)), array(
				'label' => 'Below minimum currency handling',
				'explain' => 'Choose what should happen if the currency amount falls below the minimum.',
			)) . '
				';
		} else {
			$__compilerTemp9 .= '
					' . $__templater->formNumberBoxRow(array(
				'name' => 'multmin',
				'value' => $__vars['event']['multmin'],
				'step' => 'any',
			), array(
				'label' => $__templater->escape($__vars['eventTriggerLabels']['minimum_amount']),
				'explain' => '0 = no minimum.',
			)) . '

					' . $__templater->formNumberBoxRow(array(
				'name' => 'multmax',
				'value' => $__vars['event']['multmax'],
				'step' => 'any',
			), array(
				'label' => $__templater->escape($__vars['eventTriggerLabels']['maximum_amount']),
				'explain' => 'Amounts larger than this will be reduced to this amount before being used in "Amount per X" calculations.<br />
0 = no maximum.',
			)) . '


					' . $__templater->formRadioRow(array(
				'name' => 'minaction',
				'value' => $__vars['event']['minaction'],
			), array(array(
				'value' => '0',
				'hint' => 'Only apply the "Event amount" to this event, ignoring the extra amount.',
				'label' => 'Ignore extra amount',
				'_type' => 'option',
			),
			array(
				'value' => '1',
				'label' => 'Skip this event',
				'_type' => 'option',
			),
			array(
				'value' => '2',
				'label' => ($__templater->method($__vars['eventTrigger'], 'getOption', array('canCancel', )) ? 'Display error message to the user' : 'Stop all events from this event trigger'),
				'_type' => 'option',
			)), array(
				'label' => $__templater->escape($__vars['eventTriggerLabels']['minimum_action']),
				'explain' => $__templater->escape($__vars['eventTriggerLabels']['minimum_action_explain']),
			)) . '
				';
		}
		$__compilerTemp9 .= '
			';
	}
	$__compilerTemp10 = '';
	$__compilerTemp11 = '';
	$__compilerTemp11 .= '
					' . $__templater->filter($__templater->method($__vars['eventTrigger'], 'renderOptions', array($__vars['event'], )), array(array('raw', array()),), true) . '
				';
	if (strlen(trim($__compilerTemp11)) > 0) {
		$__compilerTemp10 .= '
			<h3 class="block-formSectionHeader">
				<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
					<span class="block-formSectionHeader-aligner">' . 'Event trigger options' . '</span>
				</span>
			</h3>
			<div class="block-body block-body--collapsible">
				' . $__compilerTemp11 . '
			</div>
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">

			' . $__templater->formRow('
				' . $__templater->escape($__templater->method($__vars['eventTrigger'], 'getTitle', array())) . '
				<div class="u-muted">' . $__templater->escape($__templater->method($__vars['eventTrigger'], 'getDescription', array())) . '</div>
			', array(
		'label' => 'Event Trigger',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['event']['title_'],
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->callMacro('public:dbtech_credits_currency_macros', 'currency_select', array(
		'currencyId' => $__vars['event']['currency_id'],
	), $__vars) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'value' => '1',
		'selected' => $__vars['event']['active'],
		'label' => 'Event is active',
		'_type' => 'option',
	)), array(
	)) . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block is-active" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Event Options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible is-active">
			' . $__compilerTemp1 . '

			' . $__compilerTemp2 . '

			<hr class="formRowSep" />

			' . $__compilerTemp5 . '

			' . $__templater->formCheckBoxRow(array(
	), $__compilerTemp6, array(
	)) . '

			' . $__compilerTemp7 . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'upperrand',
		'value' => $__vars['event']['upperrand'],
		'step' => 'any',
	), array(
		'label' => 'Random addition',
		'explain' => 'A random amount between 0 and this number can be added to the event amount.<br />
Decimal and negative numbers are okay.<br />
Use 0 to disable.',
	)) . '

			' . $__compilerTemp8 . '

			' . $__compilerTemp9 . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Advanced options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__templater->formNumberBoxRow(array(
		'name' => 'frequency',
		'value' => $__vars['event']['frequency'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Frequency',
		'explain' => 'Stagger how often this event is applied. Causes this event to be applied to every Nth valid event trigger.<br />
Use 1 to apply this event every time this event trigger occurs.',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'applymax',
		'value' => $__vars['event']['applymax'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Maximum applications',
		'explain' => 'After this event has occurred this many times, the event trigger will be skipped until the below number of seconds have passed.<br />
0 = no maximum.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'applymax_peruser',
		'value' => $__vars['event']['applymax_peruser'],
	), array(array(
		'value' => '1',
		'label' => 'Limit maximum applications per-user',
		'hint' => 'If enabled, the maximum applications are tracked separately per-user, rather than across all users.',
		'_type' => 'option',
	)), array(
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'maxtime',
		'value' => $__vars['event']['maxtime'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Limit period',
		'explain' => 'Timespan in seconds that the above maximum is enforced.<br />
0 = all time.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'alert',
		'value' => '1',
		'selected' => $__vars['event']['alert'],
		'label' => 'Send alert',
		'_type' => 'option',
	),
	array(
		'name' => 'display',
		'value' => '1',
		'selected' => $__vars['event']['display'],
		'label' => 'Display in transaction log',
		'_type' => 'option',
	)), array(
	)) . '

		</div>

		' . $__compilerTemp10 . '

		' . $__templater->formHiddenVal('event_trigger_id', $__vars['event']['event_trigger_id'], array(
	)) . '

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-credits/events/save', $__vars['event'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);