<?php
// FROM HASH: 091ab1820653d8e8d17f6eb263f91bf5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Batch update groups');
	$__finalCompiled .= '

';
	if ($__vars['success']) {
		$__finalCompiled .= '
    <div class="blockMessage blockMessage--success blockMessage--iconic">' . 'The batch update was completed successfully.' . '</div>
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['categories'])) {
		foreach ($__vars['categories'] AS $__vars['category']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['category']['value'],
				'disabled' => $__vars['category']['disabled'],
				'label' => $__templater->escape($__vars['category']['label']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
                    ';
	$__compilerTemp4 = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('Truonglv\\Groups:Field', )), 'getDisplayGroups', array());
	if ($__templater->isTraversable($__compilerTemp4)) {
		foreach ($__compilerTemp4 AS $__vars['fieldGroup'] => $__vars['phrase']) {
			$__compilerTemp3 .= '
                        ';
			$__vars['customFields'] = $__templater->method($__vars['xf']['app'], 'getCustomFields', array('tl_groups', $__vars['fieldGroup'], ));
			$__compilerTemp3 .= '
                        ';
			$__compilerTemp5 = '';
			$__compilerTemp5 .= '
                                ';
			if ($__templater->isTraversable($__vars['customFields'])) {
				foreach ($__vars['customFields'] AS $__vars['fieldId'] => $__vars['fieldDefinition']) {
					$__compilerTemp5 .= '
                                    ';
					$__vars['choices'] = $__vars['fieldDefinition']['field_choices'];
					$__compilerTemp5 .= '
                                    ';
					$__vars['fieldName'] = 'criteria[group_field]' . (($__vars['choices'] AND ($__vars['fieldDefinition']['type_group'] != 'multiple')) ? '[exact]' : '') . '[' . $__vars['fieldId'] . ']';
					$__compilerTemp5 .= '
                                    ';
					$__compilerTemp6 = '';
					if (!$__vars['choices']) {
						$__compilerTemp6 .= '
                                            ' . $__templater->formTextBox(array(
							'name' => $__vars['fieldName'],
							'value' => $__vars['criteria']['group_field'][$__vars['fieldId']],
						)) . '
                                            ';
					} else {
						$__compilerTemp6 .= '
                                            ';
						$__compilerTemp7 = array();
						if ($__templater->isTraversable($__vars['choices'])) {
							foreach ($__vars['choices'] AS $__vars['val'] => $__vars['choice']) {
								$__compilerTemp7[] = array(
									'value' => (($__vars['fieldDefinition']['type_group'] == 'multiple') ? (((('s:' . $__templater->func('strlen', array($__vars['val'], ), false)) . ':"') . $__vars['val']) . '"') : $__vars['val']),
									'label' => $__templater->escape($__vars['choice']),
									'_type' => 'option',
								);
							}
						}
						$__compilerTemp6 .= $__templater->formCheckBox(array(
							'name' => $__vars['fieldName'],
							'value' => (($__vars['fieldDefinition']['type_group'] != 'multiple') ? $__vars['criteria']['group_field']['exact'][$__vars['fieldId']] : $__vars['criteria']['group_field'][$__vars['fieldId']]),
							'listclass' => 'listColumns',
						), $__compilerTemp7) . '
                                        ';
					}
					$__compilerTemp5 .= $__templater->formRow('
                                        ' . $__compilerTemp6 . '
                                    ', array(
						'rowtype' => 'input',
						'label' => $__templater->escape($__vars['fieldDefinition']['title']),
					)) . '
                                ';
				}
			}
			$__compilerTemp5 .= '
                            ';
			if (strlen(trim($__compilerTemp5)) > 0) {
				$__compilerTemp3 .= '
                            ' . $__compilerTemp5 . '
                        ';
			}
			$__compilerTemp3 .= '
                    ';
		}
	}
	$__compilerTemp3 .= '
                ';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '
                <hr class="formRowSep" />
                ' . $__compilerTemp3 . '
            ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[name]',
		'value' => $__vars['criteria']['name'],
		'type' => 'search',
	), array(
		'label' => 'Title',
	)) . '
            ' . $__templater->formTokenInputRow(array(
		'name' => 'criteria[tags]',
		'value' => $__vars['criteria']['tags'],
		'href' => $__templater->func('link_type', array('public', 'misc/tag-auto-complete', ), false),
	), array(
		'label' => 'Tags',
	)) . '

            <div class="formRowSep"></div>

            ' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[privacy]',
		'value' => $__vars['criteria']['privacy'],
	), array(array(
		'value' => 'public',
		'label' => 'Public Group',
		'_type' => 'option',
	),
	array(
		'value' => 'closed',
		'label' => 'Closed Group',
		'_type' => 'option',
	),
	array(
		'value' => 'secret',
		'label' => 'Secret Group',
		'_type' => 'option',
	)), array(
		'label' => 'Privacy',
	)) . '

            <div class="formRowSep"></div>

            ' . $__templater->formRow('

                <ul class="inputList">
                    <li>' . $__templater->formRadio(array(
		'name' => 'criteria[category_id][search_type]',
		'value' => (($__templater->func('is_array', array($__vars['criteria']['category_id'], ), false) ? $__vars['criteria']['category_id']['search_type'] : '') ?: 'include'),
		'listclass' => 'inputChoices--inline',
	), array(array(
		'value' => 'include',
		'label' => 'Include selected',
		'_type' => 'option',
	),
	array(
		'value' => 'exclude',
		'label' => 'Exclude selected',
		'_type' => 'option',
	))) . '</li>

                    <li>' . $__templater->formSelect(array(
		'name' => 'criteria[category_id]',
		'value' => ($__templater->func('is_array', array($__vars['criteria']['category_id'], ), false) ? $__templater->filter($__vars['criteria']['category_id'], array(array('numeric_keys_only', array()),), false) : $__vars['criteria']['category_id']),
		'multiple' => 'true',
		'size' => '8',
	), $__compilerTemp1) . '</li>
                </ul>
            ', array(
		'label' => 'Category',
	)) . '

            <div class="formRowSep"></div>

            ' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[owner_username]',
		'value' => $__vars['criteria']['owner_username'],
		'type' => 'search',
	), array(
		'label' => 'Created by',
	)) . '

            <div class="formRowSep"></div>

            ' . $__templater->formRow('
                <div class="inputGroup">
                    ' . $__templater->formDateInput(array(
		'name' => 'criteria[created_date][start]',
		'value' => $__vars['criteria']['created_date']['start'],
		'size' => '15',
	)) . '
                    <span class="inputGroup-text">-</span>
                    ' . $__templater->formDateInput(array(
		'name' => 'criteria[created_date][end]',
		'value' => $__vars['criteria']['created_date']['end'],
		'size' => '15',
	)) . '
                </div>
            ', array(
		'rowtype' => 'input',
		'label' => 'Created between',
	)) . '

            ' . $__templater->formRow('
                <div class="inputGroup">
                    ' . $__templater->formNumberBox(array(
		'name' => 'criteria[member_count][start]',
		'value' => $__vars['criteria']['member_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
                    <span class="inputGroup-text">-</span>
                    ' . $__templater->formNumberBox(array(
		'name' => 'criteria[member_count][end]',
		'value' => $__vars['criteria']['member_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
                </div>
            ', array(
		'rowtype' => 'input',
		'label' => 'Member count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

            ' . $__templater->formRow('
                <div class="inputGroup">
                    ' . $__templater->formNumberBox(array(
		'name' => 'criteria[event_count][start]',
		'value' => $__vars['criteria']['event_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
                    <span class="inputGroup-text">-</span>
                    ' . $__templater->formNumberBox(array(
		'name' => 'criteria[event_count][end]',
		'value' => $__vars['criteria']['event_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
                </div>
            ', array(
		'rowtype' => 'input',
		'label' => 'Event count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

            ' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[group_state]',
		'value' => $__vars['criteria']['group_state'],
	), array(array(
		'value' => 'visible',
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted',
		'label' => 'Deleted',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'label' => 'Moderated',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	)) . '

            ' . $__compilerTemp2 . '
        </div>
        ' . $__templater->formSubmitRow(array(
		'icon' => 'search',
		'sticky' => 'true',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/batch-update/confirm', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);