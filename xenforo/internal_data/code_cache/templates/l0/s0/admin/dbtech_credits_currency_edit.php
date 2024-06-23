<?php
// FROM HASH: 4d7d83d0ae0d9a7fe0fd1d07c68387c9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['currency'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add currency');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit currency' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['currency']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['currency'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-credits/currencies/delete', $__vars['currency'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['currency']['title'],
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formEditorRow(array(
		'name' => 'description',
		'value' => $__vars['currency']['description'],
		'data-min-height' => '200',
	), array(
		'label' => 'Description',
	)) . '

			' . $__templater->callMacro('display_order_macros', 'row', array(
		'value' => $__vars['currency']['display_order'],
	), $__vars) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'value' => '1',
		'selected' => $__vars['currency']['active'],
		'label' => 'Currency is active',
		'_type' => 'option',
	)), array(
	)) . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block is-active" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Currency options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible is-active">
			' . $__templater->formRow('
				xf_' . $__templater->escape($__vars['currency']['table']) . '
			', array(
		'label' => 'Table',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'column',
		'value' => $__vars['currency']['column'],
		'required' => 'true',
	), array(
		'label' => 'Column',
		'explain' => 'After creation, this should stay the same even if you change the title later or loss of data may occur.<br />
If you are manually creating this currency, please only use lower-case characters and underscores, no numbers or symbols.<br />
If this column does not exist on the above table, it will be added.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formNumberBoxRow(array(
		'name' => 'decimals',
		'value' => $__vars['currency']['decimals'],
		'min' => '0',
		'max' => '8',
		'step' => '1',
	), array(
		'label' => 'Rounding',
		'explain' => 'This is the number of decimal points you wish to round this currency to when it is publicly displayed. Internally, the actual amount will still be used in formulas.',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'negative',
		'value' => $__vars['currency']['negative'],
	), array(array(
		'value' => '0',
		'label' => 'Disallow negative currency',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Show 0 but keep negative',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => 'Allow negative display',
		'_type' => 'option',
	)), array(
		'label' => 'Negative handling',
		'explain' => 'How to treat negative amounts that sometimes occur from uncancelable events and event negation.',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'privacy',
		'value' => $__vars['currency']['privacy'],
	), array(array(
		'value' => '0',
		'label' => 'Show to designated usergroups',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Show to self and designated usergroups',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => 'Show to everyone',
		'_type' => 'option',
	)), array(
		'label' => 'Privacy filter',
		'explain' => 'The ability to see private currencies is a usergroup permission.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'prefix',
		'value' => $__vars['currency']['prefix'],
	), array(
		'label' => 'Prefix',
		'explain' => 'If you\'d like to add a symbol or text directly before the currency value, you can do so with this field.',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'suffix',
		'value' => $__vars['currency']['suffix'],
	), array(
		'label' => 'Suffix',
		'explain' => 'If you\'d like to add a symbol or text directly after the currency value, you can do so with this field.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'is_display_currency',
		'value' => '1',
		'selected' => $__vars['currency']['is_display_currency'],
		'hint' => 'If selected, this currency will be displayed on the navbar instead of the default "Credits" text.<br />
<strong>Note:</strong> Only one currency can be displayed.',
		'label' => 'Display on navbar',
		'_type' => 'option',
	),
	array(
		'name' => 'sidebar',
		'value' => '1',
		'selected' => $__vars['currency']['sidebar'],
		'hint' => 'If selected, this currency will be displayed in the tooltip shown when hovering over user names across XenForo.',
		'label' => 'Display in "member tooltip"',
		'_type' => 'option',
	),
	array(
		'name' => 'member_dropdown',
		'value' => '1',
		'selected' => $__vars['currency']['member_dropdown'],
		'hint' => 'If selected, this currency will be displayed in the drop-down users get when clicking their own user name in the navbar.',
		'label' => 'Display in "member drop-down"',
		'_type' => 'option',
	),
	array(
		'name' => 'postbit',
		'value' => '1',
		'selected' => $__vars['currency']['postbit'],
		'label' => 'Display in postbit',
		'_type' => 'option',
	),
	array(
		'name' => 'show_amounts',
		'value' => '1',
		'selected' => $__vars['currency']['show_amounts'],
		'label' => 'Show amounts in "Richest users" block',
		'_type' => 'option',
	)), array(
	)) . '

		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Advanced options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__templater->formNumberBoxRow(array(
		'name' => 'earnmax',
		'value' => $__vars['currency']['earnmax'],
		'min' => '0',
		'step' => 'any',
	), array(
		'label' => 'Maximum earned',
		'explain' => 'Users will be unable to exceed this amount earned.<br />
0 = no maximum.',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'maxtime',
		'value' => $__vars['currency']['maxtime'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Limit period',
		'explain' => 'Timespan in seconds that the above maximum is enforced.<br />
0 = all time.',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'value',
		'value' => $__vars['currency']['value'],
		'step' => 'any',
	), array(
		'label' => 'Relative value',
		'explain' => 'This is the overall value of this currency compared to all other currencies. Generally this only matters when determining total assets and transferring between currencies. You should establish at least one currency with a value of 1 as a base against the rest.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'inbound',
		'value' => '1',
		'selected' => $__vars['currency']['inbound'],
		'hint' => 'If enabled, other currencies may be transferred to this one.',
		'label' => 'Transfer to',
		'_type' => 'option',
	),
	array(
		'name' => 'outbound',
		'value' => '1',
		'selected' => $__vars['currency']['outbound'],
		'hint' => 'If enabled, this currency can be transferred to other applicable currencies.',
		'label' => 'Transfer from',
		'_type' => 'option',
	)), array(
	)) . '

		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-credits/currencies/save', $__vars['currency'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);