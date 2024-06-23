<?php
// FROM HASH: c908edf9de9e43eb704697c82b1e243c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formSelectRow(array(
		'name' => 'options[order]',
		'value' => $__vars['options']['order'],
	), array(array(
		'value' => 'create_date ',
		'label' => 'Create date',
		'_type' => 'option',
	),
	array(
		'value' => 'last_part_date',
		'label' => 'Last item date',
		'_type' => 'option',
	),
	array(
		'value' => 'item_count ',
		'label' => 'Item count',
		'_type' => 'option',
	),
	array(
		'value' => 'reaction_score',
		'label' => 'Reaction score',
		'_type' => 'option',
	),
	array(
		'value' => 'view_count',
		'label' => 'Views',
		'_type' => 'option',
	),
	array(
		'value' => 'random',
		'label' => 'Random',
		'_type' => 'option',
	)), array(
		'label' => 'Sort order',
		'explain' => 'Select the sort order to fetch items by for this widget',
	)) . '

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

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[exclude_featured]',
		'value' => '1',
		'selected' => $__vars['options']['exclude_featured'],
		'label' => 'Exclude featured series',
		'hint' => 'Checking this option will exclude any featured series from being fetched for this widget. ',
		'_type' => 'option',
	)), array(
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[cutOffDays]',
		'value' => $__vars['options']['cutOffDays'],
		'min' => '0',
	), array(
		'label' => 'Cut off days',
		'explain' => 'If sorting by <b>"Create date"</b>, this is the number of days old that a series can be in order for it to be fetched.  Series that are older than the cutoff date will not be fetch.  
<br><br>
If sorting by any of the other sort orders, this is the number of days old that the Last Series Part can be in order for it to be fetched.  Series where the Last Series Part is older than the cutoff date will not be fetch.  
<br><br>
<b>Leave this option set to 0 to bypass the cut off date.</b>',
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

' . $__templater->formTextBoxRow(array(
		'name' => 'options[block_title_link]',
		'value' => $__vars['options']['block_title_link'],
	), array(
		'label' => 'Block title link',
		'explain' => 'Add a specific URL that you want the block title to link to. <b>Leaving this blank will link to the Series Index page.</b>',
	)) . '

<hr class="formRowSep" />

' . $__templater->formTokenInputRow(array(
		'name' => 'options[tags]',
		'value' => $__vars['options']['tags'],
		'href' => $__templater->func('link_type', array('public', 'misc/tag-auto-complete', ), false),
	), array(
		'label' => 'Tags',
		'explain' => 'Only series that have these tags applied to them will be fetched.',
	));
	return $__finalCompiled;
}
);