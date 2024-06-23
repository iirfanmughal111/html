<?php
// FROM HASH: 370cd312bbe06f40f150281232ea7301
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->form('
    <div class="menu-row menu-row--separated">
        ' . 'Member status' . $__vars['xf']['language']['label_separator'] . '
        <div class="u-inputSpacer">
            ' . $__templater->formSelect(array(
		'name' => 'member_state',
		'value' => $__vars['filters']['member_state'],
	), array(array(
		'value' => 'any',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => 'valid',
		'label' => 'Joined',
		'_type' => 'option',
	),
	array(
		'value' => 'invited',
		'label' => 'Invited',
		'_type' => 'option',
	),
	array(
		'value' => 'banned',
		'label' => 'Banned',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'label' => 'Moderated',
		'_type' => 'option',
	))) . '
        </div>
    </div>

    <div class="menu-row menu-row--separated">
        ' . 'Show member staff only' . $__vars['xf']['language']['label_separator'] . '
        <div class="u-inputSpacer">
            ' . $__templater->formRadio(array(
		'name' => 'is_staff',
		'value' => ($__vars['filters']['is_staff'] ?: 0),
	), array(array(
		'value' => '1',
		'label' => 'Yes',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => 'No',
		'_type' => 'option',
	))) . '
        </div>
    </div>

    <div class="menu-row menu-row--separated">
        ' . 'Name' . $__vars['xf']['language']['label_separator'] . '
        <div class="u-inputSpacer">
            ' . $__templater->formTextBox(array(
		'name' => 'user',
		'value' => ($__vars['filterUser'] ? $__vars['filterUser']['username'] : ''),
		'ac' => 'single',
	)) . '
        </div>
    </div>

    <div class="menu-row menu-row--separated">
        ' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
        <div class="inputGroup u-inputSpacer">
            ' . $__templater->formSelect(array(
		'name' => 'order',
		'value' => ($__vars['filters']['order'] ?: 'joined_date'),
	), array(array(
		'value' => 'joined_date',
		'label' => 'Join date',
		'_type' => 'option',
	),
	array(
		'value' => 'username',
		'label' => 'Name',
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

    ' . $__templater->formHiddenVal('group_id', $__vars['group']['group_id'], array(
	)) . '
    ' . $__templater->formHiddenVal('apply', '1', array(
	)) . '
', array(
		'action' => $__templater->func('link', array('group-members/filters', ), false),
	));
	return $__finalCompiled;
}
);