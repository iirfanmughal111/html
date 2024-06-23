<?php
// FROM HASH: df99746b04aff3707160e7313d6686b8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('
    <div class="inputGroup">
        ' . $__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[order]',
		'value' => $__vars['option']['option_value']['order'],
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
		'name' => $__vars['inputName'] . '[direction]',
		'value' => $__vars['option']['option_value']['direction'],
	), array(array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	),
	array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	))) . '
    </div>
', array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);