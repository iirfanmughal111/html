<?php
// FROM HASH: 71ec3945d48beae3a905a931195eed8a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '1',
	), array(
		'label' => 'Maximum series',
		'explain' => 'The maximum amount of series to fetch and display in this widget.',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[item_count]',
		'value' => $__vars['options']['item_count'],
		'min' => '0',
	), array(
		'label' => 'Minimum series item count',
		'explain' => 'The minimum required series items.  <b>Note:</b> set to 0 to disable this option. ',
	)) . '

' . $__templater->formRadioRow(array(
		'name' => 'options[style]',
		'value' => ($__vars['options']['style'] ?: 'simple'),
	), array(array(
		'value' => 'simple',
		'label' => 'Simple',
		'hint' => 'A simple view, designed for narrow spaces such as sidebars.',
		'_type' => 'option',
	),
	array(
		'value' => 'carousel',
		'label' => 'Full' . ' - ' . 'Carousel',
		'hint' => 'A carousel view, displaying series items in a full carousel style slider. This display style is not designed for use in sidebar or sidenav positions.',
		'_type' => 'option',
	),
	array(
		'value' => 'list_view',
		'label' => 'Full' . ' - ' . 'List view',
		'hint' => 'A full size view, displaying as a standard series list using list view layout type.  This display style is not designed for use in sidebar or sidenav positions.',
		'_type' => 'option',
	)), array(
		'label' => 'Display style',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[require_series_icon]',
		'value' => '1',
		'selected' => $__vars['options']['require_series_icon'],
		'label' => 'Require series icon',
		'hint' => 'Only series that have a series icon set will be fetched. ',
		'_type' => 'option',
	)), array(
	)) . '

' . $__templater->formTokenInputRow(array(
		'name' => 'options[tags]',
		'value' => $__vars['options']['tags'],
		'href' => $__templater->func('link_type', array('public', 'misc/tag-auto-complete', ), false),
	), array(
		'label' => 'Tags',
		'explain' => 'Only items that have these tags applied to them will be fetched.  ',
	));
	return $__finalCompiled;
}
);