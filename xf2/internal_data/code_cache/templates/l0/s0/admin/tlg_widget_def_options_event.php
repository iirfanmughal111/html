<?php
// FROM HASH: b1e296904dd25c0d9a9e54a224ae45fa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formRadioRow(array(
		'name' => 'options[type]',
		'value' => $__vars['options']['type'],
	), array(array(
		'value' => 'upcoming',
		'label' => 'Upcoming events',
		'_type' => 'option',
	),
	array(
		'value' => 'ongoing',
		'label' => 'Ongoing events',
		'_type' => 'option',
	)), array(
		'label' => 'Event type',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[events_user_groups]',
		'label' => 'Only show events from groups visitor joined',
		'_type' => 'option',
	)), array(
		'label' => '',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '1',
	), array(
		'label' => 'Maximum entries',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[cache_ttl]',
		'value' => $__vars['options']['cache_ttl'],
		'min' => '0',
		'units' => 'Seconds',
	), array(
		'label' => 'Cache TTL',
	));
	return $__finalCompiled;
}
);