<?php
// FROM HASH: 1a3d576ba59b679ece4f53d59ab98abc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['currency'] = $__vars['content']['Currency'];
	$__compilerTemp1 = '';
	if ($__vars['content']['message']) {
		$__compilerTemp1 .= '
		<dl class="pairs pairs--columns pairs--fluidSmall">
			<dt>' . 'Optional message' . '</dt>
			<dd>' . $__templater->escape($__vars['content']['message']) . '</dd>
		</dl>
	';
	}
	$__vars['messageHtml'] = $__templater->preEscaped($__templater->func('trim', array('
	' . '' . '

	<dl class="pairs pairs--columns pairs--fluidSmall">
		<dt>' . 'Source User' . '</dt>
		<dd>' . $__templater->func('username_link', array($__vars['content']['SourceUser'], false, array(
		'defaultname' => 'Unknown user',
	))) . '</dd>
	</dl>
	<dl class="pairs pairs--columns pairs--fluidSmall">
		<dt>' . 'Target User' . '</dt>
		<dd>' . $__templater->func('username_link', array($__vars['content']['TargetUser'], false, array(
		'defaultname' => 'Unknown user',
	))) . '</dd>
	</dl>
	<dl class="pairs pairs--columns pairs--fluidSmall">
		<dt>' . 'Amount' . '</dt>
		<dd>' . $__templater->escape($__vars['currency']['prefix']) . $__templater->filter($__vars['content']['amount'], array(array('number', array($__vars['currency']['decimals'], )),), true) . $__templater->escape($__vars['currency']['suffix']) . ' ' . $__templater->escape($__vars['currency']['title']) . '</dd>
	</dl>

	' . $__compilerTemp1 . '
'), false));
	$__finalCompiled .= '

';
	$__vars['headerPhraseHtml'] = $__templater->preEscaped($__templater->func('trim', array('
	' . 'Event \'' . $__templater->escape($__vars['content']['Event']['title']) . '\' in event trigger \'' . $__templater->escape($__templater->method($__vars['eventTrigger'], 'getTitle', array())) . '\'' . '
'), false));
	$__finalCompiled .= '

';
	$__vars['actionsHtml'] = $__templater->preEscaped('
	
	' . $__templater->formRadio(array(
		'name' => 'queue[' . $__vars['unapprovedItem']['content_type'] . '][' . $__vars['unapprovedItem']['content_id'] . ']',
	), array(array(
		'value' => '',
		'checked' => 'checked',
		'label' => 'Do nothing',
		'data-xf-click' => 'approval-control',
		'_type' => 'option',
	),
	array(
		'value' => 'approve',
		'label' => 'Approve',
		'data-xf-click' => 'approval-control',
		'_type' => 'option',
	),
	array(
		'value' => 'reject',
		'label' => 'Reject with reason' . $__vars['xf']['language']['label_separator'],
		'title' => 'Rejected transactions will be deleted.',
		'data-xf-init' => 'tooltip',
		'data-xf-click' => 'approval-control',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'reason[' . $__vars['unapprovedItem']['content_type'] . '][' . $__vars['unapprovedItem']['content_id'] . ']',
		'placeholder' => 'Optional',
	))),
		'html' => '
				<div class="formRow-explain"></div>
			',
		'_type' => 'option',
	))) . '

	' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'notify[' . $__vars['unapprovedItem']['content_type'] . '][' . $__vars['unapprovedItem']['content_id'] . ']',
		'value' => '1',
		'checked' => 'true',
		'label' => '
			' . 'Notify user if action was taken' . '
		',
		'_type' => 'option',
	))) . '

');
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['dateline'],
		'user' => $__vars['content']['TargetUser'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Transaction',
		'actionsHtml' => $__vars['actionsHtml'],
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'headerPhraseHtml' => $__vars['headerPhraseHtml'],
	), $__vars);
	return $__finalCompiled;
}
);