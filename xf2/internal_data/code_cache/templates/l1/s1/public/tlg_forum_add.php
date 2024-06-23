<?php
// FROM HASH: f626b304d8a76791d9be24f5aa5833d3
return array(
'macros' => array('title' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ' . $__templater->formTextBoxRow(array(
		'name' => 'node[title]',
		'value' => $__vars['node']['title'],
	), array(
		'label' => 'Title',
	)) . '
';
	return $__finalCompiled;
}
),
'description' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ' . $__templater->formTextAreaRow(array(
		'name' => 'node[description]',
		'value' => $__vars['node']['description'],
		'autosize' => 'true',
	), array(
		'label' => 'Description',
	)) . '
';
	return $__finalCompiled;
}
),
'position' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
		'nodeTree' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['node_id'],
				'label' => $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'node[parent_node_id]',
		'value' => $__vars['node']['parent_node_id'],
	), $__compilerTemp1, array(
		'label' => 'Parent node',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__templater->method($__vars['forum'], 'exists', array()) ? 'Edit forum' : 'Add forum'));
	$__finalCompiled .= '

';
	if (!$__vars['withData']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = $__templater->preEscaped('discussions');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->callMacro(null, 'title', array(
		'node' => $__vars['node'],
	), $__vars) . '
            ' . $__templater->callMacro(null, 'description', array(
		'node' => $__vars['node'],
	), $__vars) . '

            <hr class="formRowSep" />
            ' . $__templater->callMacro(null, 'position', array(
		'node' => $__vars['node'],
		'nodeTree' => $__vars['nodeTree'],
	), $__vars) . '
            <hr class="formRowSep" />

            ' . $__templater->formNumberBoxRow(array(
		'name' => 'min_tags',
		'value' => $__vars['forum']['min_tags'],
		'min' => '0',
		'max' => '100',
	), array(
		'label' => 'Minimum required tags',
		'explain' => 'This will require users to provide at least this many tags when creating a thread.',
	)) . '

            ' . $__templater->formRadioRow(array(
		'name' => 'allowed_watch_notifications',
		'value' => $__vars['forum']['allowed_watch_notifications'],
	), array(array(
		'value' => 'all',
		'label' => 'New messages',
		'_type' => 'option',
	),
	array(
		'value' => 'thread',
		'label' => 'New threads',
		'_type' => 'option',
	),
	array(
		'value' => 'none',
		'label' => 'None',
		'_type' => 'option',
	)), array(
		'label' => 'Forum watch notification limit',
		'explain' => 'You can limit the amount of notifications that can be triggered by a user watching a forum here. For example, if you select "new threads", users will only be able to choose between no notifications or notifications when a new thread is posted. This can be used to limit the overhead of the forum watching system in busy forums.',
	)) . '

            ' . $__templater->formRow('

                <div class="inputPair">
                    ' . $__templater->formSelect(array(
		'name' => 'default_sort_order',
		'value' => $__vars['forum']['default_sort_order'],
		'class' => 'input--inline',
	), array(array(
		'value' => 'last_post_date',
		'label' => 'Last message',
		'_type' => 'option',
	),
	array(
		'value' => 'post_date',
		'label' => 'Start date',
		'_type' => 'option',
	),
	array(
		'value' => 'title',
		'label' => 'Title',
		'_type' => 'option',
	),
	array(
		'value' => 'reply_count',
		'label' => 'Replies',
		'_type' => 'option',
	),
	array(
		'value' => 'view_count',
		'label' => 'Views',
		'_type' => 'option',
	))) . '
                    ' . $__templater->formSelect(array(
		'name' => 'default_sort_direction',
		'value' => $__vars['forum']['default_sort_direction'],
		'class' => 'input--inline',
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
            ', array(
		'rowtype' => 'input',
		'label' => 'Default sort order',
	)) . '

            ' . $__templater->formSelectRow(array(
		'name' => 'list_date_limit_days',
		'value' => $__vars['forum']['list_date_limit_days'],
	), array(array(
		'value' => '0',
		'label' => 'None',
		'_type' => 'option',
	),
	array(
		'value' => '7',
		'label' => '' . '7' . ' days',
		'_type' => 'option',
	),
	array(
		'value' => '14',
		'label' => '' . '14' . ' days',
		'_type' => 'option',
	),
	array(
		'value' => '30',
		'label' => '' . '30' . ' days',
		'_type' => 'option',
	),
	array(
		'value' => '60',
		'label' => '' . '2' . ' months',
		'_type' => 'option',
	),
	array(
		'value' => '90',
		'label' => '' . '3' . ' months',
		'_type' => 'option',
	),
	array(
		'value' => '182',
		'label' => '' . '6' . ' months',
		'_type' => 'option',
	),
	array(
		'value' => '365',
		'label' => '1 year',
		'_type' => 'option',
	)), array(
		'label' => 'Thread list date limit',
		'explain' => 'This can be used on busy forums to improve performance by only listing recently updated threads by default.',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
    </div>
', array(
		'action' => $__vars['formAction'],
		'class' => 'block',
		'ajax' => 'true',
	)) . '

' . '

' . '

';
	return $__finalCompiled;
}
);