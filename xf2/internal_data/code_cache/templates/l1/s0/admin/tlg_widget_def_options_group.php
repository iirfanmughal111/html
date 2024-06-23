<?php
// FROM HASH: 7bd4f7974e2a7ab0e42871d22bc60d41
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formRadioRow(array(
		'name' => 'options[order]',
		'value' => $__vars['options']['order'],
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
	)), array(
		'label' => 'Order by',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '1',
	), array(
		'label' => 'Maximum entries',
	)) . '

' . $__templater->formRadioRow(array(
		'name' => 'options[style]',
		'value' => ($__vars['options']['style'] ?: 'simple'),
	), array(array(
		'value' => 'simple',
		'label' => 'Simple',
		'_type' => 'option',
	),
	array(
		'value' => 'full',
		'label' => 'Full',
		'_dependent' => array('
            <div>' . 'Items Per Row' . '</div>
            ' . $__templater->formNumberBox(array(
		'name' => 'options[itemsPerRow]',
		'value' => $__vars['options']['itemsPerRow'],
		'min' => '1',
		'max' => '5',
	)) . '
            <div>' . 'Maximum members show in card' . '</div>
            ' . $__templater->formNumberBox(array(
		'name' => 'options[max_members]',
		'value' => $__vars['options']['max_members'],
		'min' => '0',
		'max' => '6',
	)) . '
        '),
		'_type' => 'option',
	)), array(
		'label' => 'Display style',
	)) . '

';
	$__compilerTemp1 = array(array(
		'value' => '',
		'label' => 'All categories',
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'label' => '
            ' . $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . $__templater->escape($__vars['treeEntry']['record']['title']) . '
        ',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[category_ids][]',
		'value' => ($__vars['options']['category_ids'] ?: ''),
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp1, array(
		'label' => 'Category limit',
		'explain' => 'Only include groups in selected categories',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[featured_only]',
		'selected' => $__vars['options']['featured_only'],
		'label' => 'Show only featured groups',
		'_type' => 'option',
	),
	array(
		'name' => 'options[use_guest]',
		'selected' => $__vars['options']['use_guest'],
		'hint' => 'If enabled, widget only contains groups which viewable by guest.',
		'label' => 'Use guest permissions',
		'_type' => 'option',
	),
	array(
		'name' => 'options[user_groups_only]',
		'selected' => $__vars['options']['user_groups_only'],
		'hint' => 'This option does not work with option `Use guest permissions` enabled.',
		'label' => 'Show only groups which user (visitor) joined',
		'_type' => 'option',
	)), array(
		'label' => '',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[ttl]',
		'value' => $__vars['options']['ttl'],
		'min' => '0',
		'units' => 'Minutes',
		'max' => '60',
	), array(
		'label' => 'Cache TTL',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[custom_template]',
		'value' => $__vars['options']['custom_template'],
	), array(
		'label' => 'Custom template',
		'explain' => 'The template used to render widget content.',
	));
	return $__finalCompiled;
}
);