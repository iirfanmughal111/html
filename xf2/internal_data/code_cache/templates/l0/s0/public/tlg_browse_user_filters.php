<?php
// FROM HASH: 6de12481aa1edc2493c4e88977dd0dc6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'value' => '',
		'label' => 'Any',
		'_type' => 'option',
	)
,array(
		'value' => 'admin',
		'label' => 'Admin of groups',
		'_type' => 'option',
	));
	if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1[] = array(
			'value' => 'invited',
			'label' => 'Invited groups',
			'_type' => 'option',
		);
	}
	$__finalCompiled .= $__templater->form('
    <div class="menu-row menu-row--separated menu-row--privacy">
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

    <div class="menu-row menu-row--separated menu-row--type">
        ' . 'Type' . $__vars['xf']['language']['label_separator'] . '
        ' . $__templater->formSelect(array(
		'name' => 'type',
		'value' => $__vars['filters']['type'],
	), $__compilerTemp1) . '
    </div>

    <div class="menu-row menu-row--separated menu-row--sort">
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

    ' . $__templater->formHiddenVal('user_id', $__vars['user']['user_id'], array(
	)) . '
', array(
		'action' => $__templater->func('link', array('groups/browse/user/filters', ), false),
	));
	return $__finalCompiled;
}
);