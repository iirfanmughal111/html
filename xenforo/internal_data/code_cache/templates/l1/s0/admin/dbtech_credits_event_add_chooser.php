<?php
// FROM HASH: eed2ccf55a44cf8aaee19bb86dcafaa1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add event' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('public:dbtech_credits_event_macros', 'event_trigger_select', array(
		'eventTriggerId' => '',
		'eventTriggers' => $__vars['eventTriggers'],
	), $__vars) . '
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'add',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-credits/events/add', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);