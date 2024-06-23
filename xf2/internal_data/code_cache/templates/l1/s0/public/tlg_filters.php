<?php
// FROM HASH: c9f167a90bda653b0c6be96db66ec437
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['languages'], 'empty', array())) {
		$__compilerTemp1 .= '
        <div class="menu-row menu-row--separated">
            ' . 'Language' . '
            <div class="u-inputSpacer">
                ';
		$__compilerTemp2 = array(array(
			'value' => '',
			'label' => 'Any',
			'_type' => 'option',
		));
		$__compilerTemp2 = $__templater->mergeChoiceOptions($__compilerTemp2, $__vars['languages']);
		$__compilerTemp1 .= $__templater->formSelect(array(
			'name' => 'language_code',
			'value' => $__vars['filters']['language_code'],
		), $__compilerTemp2) . '
            </div>
        </div>
    ';
	}
	$__finalCompiled .= $__templater->form('
    <!-- TLG_FILTERS: TOP -->
    <!-- TLG_FILTERS:above_privacy -->
    <div class="menu-row menu-row--separated">
        ' . 'Privacy' . $__vars['xf']['language']['label_separator'] . '
        <div class="u-inputSpacer">
            ' . $__templater->formSelect(array(
		'name' => 'privacy',
		'value' => $__vars['filters']['privacy'],
	), array(array(
		'value' => '',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
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
	))) . '
        </div>
    </div>
    <!-- TLG_FILTERS:above_created_by -->
    <div class="menu-row menu-row--separated">
        ' . 'Created by' . $__vars['xf']['language']['label_separator'] . '
        <div class="u-inputSpacer">
            ' . $__templater->formTextBox(array(
		'name' => 'creator',
		'value' => ($__vars['creatorFilter'] ? $__vars['creatorFilter']['username'] : ''),
		'ac' => 'single',
	)) . '
        </div>
    </div>

    <!-- TLG_FILTERS:above_language -->
    ' . $__compilerTemp1 . '

    <!-- TLG_FILTERS:above_sort_by -->
    <div class="menu-row menu-row--separated">
        ' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
        <div class="inputGroup u-inputSpacer">
            ' . $__templater->formSelect(array(
		'name' => 'order',
		'value' => ($__vars['filters']['order'] ?: 'last_activity'),
	), array(array(
		'value' => 'created_date',
		'label' => 'Submission date',
		'_type' => 'option',
	),
	array(
		'value' => 'name',
		'label' => 'Alphabetically',
		'_type' => 'option',
	),
	array(
		'value' => 'member_count',
		'label' => 'Member count',
		'_type' => 'option',
	),
	array(
		'value' => 'view_count',
		'label' => 'View count',
		'_type' => 'option',
	),
	array(
		'value' => 'event_count',
		'label' => 'Event count',
		'_type' => 'option',
	),
	array(
		'value' => 'discussion_count',
		'label' => 'Discussion count',
		'_type' => 'option',
	),
	array(
		'value' => 'last_activity',
		'label' => 'Last activity',
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
    <!-- TLG_FILTERS: BOTTOM -->

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
		'action' => $__vars['formAction'],
	));
	return $__finalCompiled;
}
);