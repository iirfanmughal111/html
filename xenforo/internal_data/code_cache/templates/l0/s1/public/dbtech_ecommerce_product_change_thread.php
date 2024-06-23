<?php
// FROM HASH: c04a204f89a5dfdb99c624540eb69a71
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Change discussion thread');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['product'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['product']['discussion_thread_id']) {
		$__compilerTemp1 .= '
				' . $__templater->formRadioRow(array(
			'name' => 'thread_action',
			'value' => 'update',
		), array(array(
			'value' => 'update',
			'label' => 'Update discussion thread' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->formTextBox(array(
			'name' => 'thread_url',
			'value' => ($__templater->method($__vars['produt'], 'hasViewableDiscussion', array()) ? $__templater->func('link', array('full:threads', $__vars['product']['Discussion'], ), false) : ''),
			'placeholder' => 'Thread URL',
		))),
			'_type' => 'option',
		),
		array(
			'value' => 'disconnect',
			'label' => 'Disconnect existing discussion',
			'_type' => 'option',
		)), array(
			'label' => 'Action',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'thread_url',
		), array(
			'label' => 'Discussion thread URL',
		)) . '
				' . $__templater->formHiddenVal('thread_action', 'update', array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/change-thread', $__vars['product'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);