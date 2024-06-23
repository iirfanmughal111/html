<?php
// FROM HASH: c0d816fdf8ed40693ddd1e39812cec65
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__templater->method($__vars['group'], 'exists', array()) ? 'Edit group' : 'Add new group'));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                    ' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'tl_groups',
		'set' => $__vars['group']['custom_fields'],
		'group' => 'above_info',
		'editMode' => $__templater->method($__vars['group'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
                ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
                ' . $__compilerTemp2 . '

                <hr class="formRowSep" />
            ';
	}
	$__compilerTemp3 = '';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
                    ' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit_groups', array(
		'type' => 'tl_groups',
		'set' => $__vars['group']['custom_fields'],
		'groups' => array('below_info', 'extra_tab', 'new_tab', ),
		'editMode' => $__templater->method($__vars['group'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
                ';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__compilerTemp3 .= '
                ' . $__compilerTemp4 . '
            ';
	}
	$__compilerTemp5 = '';
	if (!$__vars['group']['group_id']) {
		$__compilerTemp5 .= '
                <hr class="formRowSep" />
                ' . $__templater->callMacro('tlg_group_privacy_update', 'group_privacy_rows', array(
			'group' => $__vars['group'],
			'category' => $__vars['category'],
		), $__vars) . '
                <!-- TLG_GROUP_ADD:BELOW_PRIVACY -->
            ';
	}
	$__compilerTemp6 = '';
	$__compilerTemp7 = '';
	$__compilerTemp7 .= '
                    ';
	if ($__vars['xf']['options']['tl_groups_enableLanguage'] > 0) {
		$__compilerTemp7 .= '
                        ';
		$__compilerTemp8 = array();
		if ($__templater->isTraversable($__vars['languages'])) {
			foreach ($__vars['languages'] AS $__vars['languageCode'] => $__vars['languageName']) {
				$__compilerTemp8[] = array(
					'value' => $__vars['languageCode'],
					'label' => $__templater->escape($__vars['languageName']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp7 .= $__templater->formSelectRow(array(
			'name' => 'language_code',
			'value' => $__vars['group']['language_code'],
		), $__compilerTemp8, array(
			'label' => 'Language',
			'explain' => 'The primary language used in your group.',
		)) . '
                    ';
	}
	$__compilerTemp7 .= '
                    ';
	if ($__vars['canEditTags']) {
		$__compilerTemp7 .= '
                        ' . $__templater->callMacro('tag_macros', 'edit_rows', array(
			'uneditableTags' => ($__vars['uneditableTags'] ?: null),
			'editableTags' => ($__vars['editableTags'] ?: null),
		), $__vars) . '
                    ';
	}
	$__compilerTemp7 .= '
                ';
	if (strlen(trim($__compilerTemp7)) > 0) {
		$__compilerTemp6 .= '
                <hr class="formRowSep" />
                ' . $__compilerTemp7 . '
            ';
	}
	$__compilerTemp9 = '';
	if (!$__vars['group']['group_id']) {
		$__compilerTemp9 .= '
        ' . $__templater->formHiddenVal('category_id', $__vars['category']['category_id'], array(
		)) . '
    ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formTextBoxRow(array(
		'name' => 'name',
		'value' => $__vars['group']['name'],
		'maxlength' => $__templater->func('max_length', array($__vars['group'], 'name', ), false),
		'class' => 'input--title',
		'placeholder' => 'Name your group',
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
            <!-- TLG_GROUP_ADD:BELOW_GROUP_NAME -->

            ' . $__compilerTemp1 . '

            ' . $__templater->formTextAreaRow(array(
		'name' => 'short_description',
		'value' => $__vars['group']['short_description'],
		'maxlength' => $__templater->func('max_length', array($__vars['group'], 'short_description', ), false),
		'rows' => '2',
		'placeholder' => 'Enter your best group description to help other people understand your group.',
	), array(
		'rowtype' => 'fullWidth noLabel mergePrev',
	)) . '
            <!-- TLG_GROUP_ADD:BELOW_SHORT_DESCRIPTION -->

            ' . $__templater->formEditorRow(array(
		'name' => 'description',
		'value' => $__vars['group']['description'],
	), array(
		'label' => 'Description',
		'rowtype' => 'fullWidth noLabel mergePrev',
	)) . '
            <!-- TLG_GROUP_ADD:BELOW_DESCRIPTION -->

            ' . $__compilerTemp3 . '

            ' . $__compilerTemp5 . '

            ' . $__compilerTemp6 . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>

    ' . $__compilerTemp9 . '
', array(
		'action' => ($__templater->method($__vars['group'], 'exists', array()) ? $__templater->func('link', array('groups/edit', $__vars['group'], ), false) : $__templater->func('link', array('group-categories/add', $__vars['category'], ), false)),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);