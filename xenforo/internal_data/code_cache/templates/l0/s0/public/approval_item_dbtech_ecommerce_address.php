<?php
// FROM HASH: 8aa2fe517a4ca23fac157288e5fd0fa2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['messageHtml'] = $__templater->preEscaped($__templater->func('trim', array('
	<dl class="pairs pairs--columns pairs--fluidSmall">
		<dt>' . 'VAT registration number' . '</dt>
		<dd>' . $__templater->escape($__vars['content']['sales_tax_id']) . '</dd>
	</dl>
	<dl class="pairs pairs--columns pairs--fluidSmall">
		<dt>' . 'Address' . '</dt>
		<dd>
			' . $__templater->escape($__vars['content']['business_title']) . '<br />
			' . $__templater->escape($__vars['content']['business_co']) . '<br />
			' . $__templater->escape($__vars['content']['address1']) . '<br />
			' . $__templater->escape($__vars['content']['address2']) . '<br />
			' . $__templater->escape($__vars['content']['address3']) . '<br />
			' . $__templater->escape($__vars['content']['address4']) . '<br />
			' . $__templater->escape($__vars['content']['Country']['name']) . ' (' . $__templater->escape($__vars['content']['Country']['native_name']) . ')
		</dd>
	</dl>
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
		'title' => 'Rejected addresses will not be deleted, but will have their VAT IDs removed. The reason for rejection, if entered here, will be displayed in the alert given to the user.',
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
		'contentDate' => $__vars['unapprovedItem']['content_date'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Address',
		'actionsHtml' => $__vars['actionsHtml'],
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'headerPhraseHtml' => $__vars['address']['title'],
	), $__vars);
	return $__finalCompiled;
}
);