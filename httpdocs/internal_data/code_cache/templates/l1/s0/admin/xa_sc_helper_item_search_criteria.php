<?php
// FROM HASH: d2bac4d35cf3aaf576466bdbf7db1541
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[title]',
		'value' => $__vars['criteria']['title'],
		'type' => 'search',
	), array(
		'label' => 'Title',
	)) . '

' . $__templater->callMacro('public:prefix_macros', 'row', array(
		'includeAny' => true,
		'prefixes' => $__vars['prefixes']['prefixesGrouped'],
		'selected' => $__vars['criteria']['prefix_id'],
		'name' => 'criteria[prefix_id]',
		'type' => 'sc_item',
		'multiple' => true,
	), $__vars) . '

' . $__templater->formTokenInputRow(array(
		'name' => 'criteria[tags]',
		'value' => $__vars['criteria']['tags'],
		'href' => $__templater->func('link_type', array('public', 'misc/tag-auto-complete', ), false),
	), array(
		'label' => 'Tags',
	)) . '

<div class="formRowSep"></div>

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
	$__finalCompiled .= $__templater->formRow('

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
		'name' => 'criteria[username]',
		'value' => $__vars['criteria']['username'],
		'type' => 'search',
	), array(
		'label' => 'Created by',
	)) . '

';
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['userGroups'])) {
		foreach ($__vars['userGroups'] AS $__vars['userGroup']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['userGroup']['user_group_id'],
				'label' => $__templater->escape($__vars['userGroup']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
		'name' => 'criteria[author_user_group_id]',
		'value' => $__vars['criteria']['author_user_group_id'],
		'listclass' => 'listColumns',
	), $__compilerTemp2, array(
		'label' => 'Item author in user groups',
		'explain' => 'True if the item author is in one or more of the selected groups.',
	)) . '

';
	$__compilerTemp3 = array();
	if ($__templater->isTraversable($__vars['userGroups'])) {
		foreach ($__vars['userGroups'] AS $__vars['userGroup']) {
			$__compilerTemp3[] = array(
				'value' => $__vars['userGroup']['user_group_id'],
				'label' => $__templater->escape($__vars['userGroup']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
		'name' => 'criteria[author_not_user_group_id]',
		'value' => $__vars['criteria']['author_not_user_group_id'],
		'listclass' => 'listColumns',
	), $__compilerTemp3, array(
		'label' => 'Item author not in user groups',
		'explain' => 'True if the item author is not in any of the selected groups.',
	)) . '

<hr class="formRowSep" />

' . $__templater->formRow('
	<div class="inputGroup">
		' . $__templater->formDateInput(array(
		'name' => 'criteria[create_date][start]',
		'value' => $__vars['criteria']['create_date']['start'],
		'size' => '15',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formDateInput(array(
		'name' => 'criteria[create_date][end]',
		'value' => $__vars['criteria']['create_date']['end'],
		'size' => '15',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Created between',
	)) . '

' . $__templater->formRow('
	<div class="inputGroup inputGroup--auto">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[created_in_last][value]',
		'value' => $__vars['criteria']['created_in_last']['value'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-splitter"></span>
		' . $__templater->formSelect(array(
		'name' => 'criteria[created_in_last][unit]',
		'value' => $__vars['criteria']['created_in_last']['unit'],
	), array(array(
		'value' => 'hour',
		'label' => 'Hours',
		'_type' => 'option',
	),
	array(
		'value' => 'day',
		'label' => 'Days',
		'_type' => 'option',
	),
	array(
		'value' => 'week',
		'label' => 'Weeks',
		'_type' => 'option',
	),
	array(
		'value' => 'month',
		'label' => 'Months',
		'_type' => 'option',
	),
	array(
		'value' => 'year',
		'label' => 'Years',
		'_type' => 'option',
	))) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Created in last',
		'explain' => 'Use 0 to specify no restriction.',
	)) . '

<hr class="formRowSep" />

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[comment_count][start]',
		'value' => $__vars['criteria']['comment_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[comment_count][end]',
		'value' => $__vars['criteria']['comment_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Comment count between
',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[rating_count][start]',
		'value' => $__vars['criteria']['rating_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[rating_count][end]',
		'value' => $__vars['criteria']['rating_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Rating count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[review_count][start]',
		'value' => $__vars['criteria']['review_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[review_count][end]',
		'value' => $__vars['criteria']['review_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Review count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[update_count][start]',
		'value' => $__vars['criteria']['update_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[update_count][end]',
		'value' => $__vars['criteria']['update_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Update count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[reaction_score][start]',
		'value' => $__vars['criteria']['reaction_score']['start'],
		'size' => '5',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[reaction_score][end]',
		'value' => $__vars['criteria']['reaction_score']['end'],
		'size' => '5',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Reaction score between',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[view_count][start]',
		'value' => $__vars['criteria']['view_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[view_count][end]',
		'value' => $__vars['criteria']['view_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'View count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

<hr class="formRowSep" />

' . $__templater->formRow('
	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[Discussion][reply_count][start]',
		'value' => $__vars['criteria']['Discussion']['reply_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[Discussion][reply_count][end]',
		'value' => $__vars['criteria']['Discussion']['reply_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Associated discussion thread reply count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

<hr class="formRowSep" />

' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[item_state]',
	), array(array(
		'value' => 'visible',
		'selected' => $__templater->func('in_array', array('visible', $__vars['criteria']['item_state'], ), false),
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted',
		'selected' => $__templater->func('in_array', array('deleted', $__vars['criteria']['item_state'], ), false),
		'label' => 'Deleted',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'selected' => $__templater->func('in_array', array('moderated', $__vars['criteria']['item_state'], ), false),
		'label' => 'Moderated',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	)) . '

' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[comments_open]',
	), array(array(
		'value' => '1',
		'selected' => $__templater->func('in_array', array(1, $__vars['criteria']['comments_open'], ), false),
		'label' => 'Unlocked',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'selected' => $__templater->func('in_array', array(0, $__vars['criteria']['comments_open'], ), false),
		'label' => 'Locked',
		'_type' => 'option',
	)), array(
		'label' => 'Comments locked',
	)) . '

' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[ratings_open]',
	), array(array(
		'value' => '1',
		'selected' => $__templater->func('in_array', array(1, $__vars['criteria']['ratings_open'], ), false),
		'label' => 'Unlocked',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'selected' => $__templater->func('in_array', array(0, $__vars['criteria']['ratings_open'], ), false),
		'label' => 'Locked',
		'_type' => 'option',
	)), array(
		'label' => 'Ratings locked',
	)) . '

';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
		';
	$__compilerTemp5 = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('XenAddons\\Showcase:ItemField', )), 'getDisplayGroups', array());
	if ($__templater->isTraversable($__compilerTemp5)) {
		foreach ($__compilerTemp5 AS $__vars['fieldGroup'] => $__vars['phrase']) {
			$__compilerTemp4 .= '
			';
			$__vars['customFields'] = $__templater->method($__vars['xf']['app'], 'getCustomFields', array('sc_items', $__vars['fieldGroup'], ));
			$__compilerTemp4 .= '
			';
			$__compilerTemp6 = '';
			$__compilerTemp6 .= '
					';
			if ($__templater->isTraversable($__vars['customFields'])) {
				foreach ($__vars['customFields'] AS $__vars['fieldId'] => $__vars['fieldDefinition']) {
					$__compilerTemp6 .= '
						';
					$__vars['choices'] = $__vars['fieldDefinition']['field_choices'];
					$__compilerTemp6 .= '
						';
					$__vars['fieldName'] = 'criteria[item_field]' . ((($__vars['choices'] AND ($__vars['fieldDefinition']['type_group'] != 'multiple'))) ? '[exact]' : '') . '[' . $__vars['fieldId'] . ']';
					$__compilerTemp6 .= '
						';
					$__compilerTemp7 = '';
					if (!$__vars['choices']) {
						$__compilerTemp7 .= '
								' . $__templater->formTextBox(array(
							'name' => $__vars['fieldName'],
							'value' => $__vars['criteria'][$__vars['fieldName']]['text'],
						)) . '
							';
					} else {
						$__compilerTemp7 .= '
								';
						$__compilerTemp8 = array();
						if ($__templater->isTraversable($__vars['choices'])) {
							foreach ($__vars['choices'] AS $__vars['val'] => $__vars['choice']) {
								$__compilerTemp8[] = array(
									'value' => (($__vars['fieldDefinition']['type_group'] == 'multiple') ? (((('s:' . $__templater->func('strlen', array($__vars['val'], ), false)) . ':"') . $__vars['val']) . '"') : $__vars['val']),
									'label' => $__templater->escape($__vars['choice']),
									'_type' => 'option',
								);
							}
						}
						$__compilerTemp7 .= $__templater->formCheckBox(array(
							'name' => $__vars['fieldName'],
							'value' => $__vars['criteria']['custom'][$__vars['fieldId']],
							'listclass' => 'listColumns',
						), $__compilerTemp8) . '
							';
					}
					$__compilerTemp6 .= $__templater->formRow('
							' . $__compilerTemp7 . '
						', array(
						'rowtype' => 'input',
						'label' => $__templater->escape($__vars['fieldDefinition']['title']),
					)) . '
					';
				}
			}
			$__compilerTemp6 .= '
				';
			if (strlen(trim($__compilerTemp6)) > 0) {
				$__compilerTemp4 .= '
				' . $__compilerTemp6 . '
			';
			}
			$__compilerTemp4 .= '
		';
		}
	}
	$__compilerTemp4 .= '
	';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__finalCompiled .= '
	<hr class="formRowSep" />
	' . $__compilerTemp4 . '
';
	}
	return $__finalCompiled;
}
);