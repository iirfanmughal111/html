<?php
// FROM HASH: 4f0d1c0d832bb80fd2ebcb27adba1296
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

';
	$__compilerTemp1 = array(array(
		'value' => '',
		'label' => 'All categories',
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'label' => $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[category_ids][]',
		'size' => '7',
		'multiple' => 'multiple',
		'value' => $__vars['options']['category_ids'],
	), $__compilerTemp1, array(
		'label' => 'Display from categories',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[include_personal_albums]',
		'selected' => $__vars['options']['include_personal_albums'],
		'hint' => 'Only media from viewable albums will be displayed.',
		'label' => 'Display from personal albums',
		'_type' => 'option',
	)), array(
	)) . '

' . $__templater->formRadioRow(array(
		'name' => 'options[order]',
		'value' => $__vars['options']['order'],
	), array(array(
		'value' => 'latest',
		'label' => 'Latest media',
		'_type' => 'option',
	),
	array(
		'value' => 'random',
		'label' => 'Random media',
		'_type' => 'option',
	)), array(
		'label' => 'Display order',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '1',
	), array(
		'label' => 'Number of media items',
		'explain' => 'This is the total number of media items that will be loaded in the slider.',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[slider][item]',
		'value' => $__vars['options']['slider']['item'],
		'min' => '1',
	), array(
		'label' => 'Maximum slides',
		'explain' => 'This is the maximum number of slides that will be shown at a time.',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'data-hide' => 'true',
		'selected' => ($__vars['options']['slider']['itemWide'] OR $__vars['options']['slider']['itemMedium']) OR $__vars['options']['slider']['itemNarrow'],
		'label' => 'Enable responsive slides',
		'_dependent' => array('
			<dl class="inputLabelPair">
				<dt><label for="ctrl_wide">' . 'Maximum slides' . ' ' . $__vars['xf']['language']['parenthesis_open'] . 'Wide' . $__vars['xf']['language']['parenthesis_close'] . '</label></dt>
				<dd>' . $__templater->formNumberBox(array(
		'name' => 'options[slider][itemWide]',
		'id' => 'ctrl_wide',
		'value' => $__vars['options']['slider']['itemWide'],
		'min' => '0',
		'required' => false,
	)) . '</dd>
			</dl>
			<dl class="inputLabelPair">
				<dt><label for="ctrl_medium">' . 'Maximum slides' . ' ' . $__vars['xf']['language']['parenthesis_open'] . 'Medium' . $__vars['xf']['language']['parenthesis_close'] . '</label></dt>
				<dd>' . $__templater->formNumberBox(array(
		'name' => 'options[slider][itemMedium]',
		'id' => 'ctrl_medium',
		'value' => $__vars['options']['slider']['itemMedium'],
		'min' => '0',
		'required' => false,
	)) . '</dd>
			</dl>
			<dl class="inputLabelPair">
				<dt><label for="ctrl_narrow">' . 'Maximum slides' . ' ' . $__vars['xf']['language']['parenthesis_open'] . 'Narrow' . $__vars['xf']['language']['parenthesis_close'] . '</label></dt>
				<dd>' . $__templater->formNumberBox(array(
		'name' => 'options[slider][itemNarrow]',
		'id' => 'ctrl_narrow',
		'value' => $__vars['options']['slider']['itemNarrow'],
		'min' => '0',
		'required' => false,
	)) . '</dd>
			</dl>
		'),
		'afterhint' => 'The maximum number of slides displayed at a time can be flexible depending on viewport size. If checked, leave fields blank or 0 to not change the value for a particular breakpoint.',
		'_type' => 'option',
	)), array(
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[slider][auto]',
		'selected' => $__vars['options']['slider']['auto'],
		'hint' => 'Slider will automatically play slides.',
		'label' => '
		' . 'Auto-play' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => 'options[slider][pauseOnHover]',
		'selected' => $__vars['options']['slider']['pauseOnHover'],
		'hint' => 'If auto-play is enabled, pause when hovering over the slider.',
		'label' => '
		' . 'Pause on hover' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => 'options[slider][loop]',
		'selected' => $__vars['options']['slider']['loop'],
		'hint' => 'Allow slider to loop back to the beginning when the end is reached.',
		'label' => '
		' . 'Loop slides' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => 'options[slider][pager]',
		'selected' => $__vars['options']['slider']['pager'],
		'hint' => 'Display pager buttons below the slider.',
		'label' => '
		' . 'Display pager' . '
	',
		'_type' => 'option',
	)), array(
	));
	return $__finalCompiled;
}
);