<?php
// FROM HASH: 495a212111b6a86792cbbb3a977bebfb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['item']['discussion_thread_id']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Change discussion thread');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add discussion thread');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['item']['discussion_thread_id']) {
		$__compilerTemp1 .= '
				' . $__templater->formRadioRow(array(
			'name' => 'thread_action',
			'value' => 'update',
		), array(array(
			'value' => 'update',
			'label' => 'Update discussion thread' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->formTextBox(array(
			'name' => 'thread_url',
			'value' => ($__templater->method($__vars['item'], 'hasViewableDiscussion', array()) ? $__templater->func('link', array('full:threads', $__vars['item']['Discussion'], ), false) : ''),
			'placeholder' => 'Thread URL',
		))),
			'afterhint' => '',
			'_type' => 'option',
		),
		array(
			'value' => 'disconnect',
			'label' => 'Disconnect existing discussion',
			'hint' => '',
			'_type' => 'option',
		)), array(
			'label' => 'Action',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = array(array(
			'value' => 'update',
			'label' => 'Associate with an existing discussion thread' . $__vars['xf']['language']['label_separator'],
			'hint' => '',
			'_dependent' => array($__templater->formTextBox(array(
			'name' => 'thread_url',
			'value' => '',
			'placeholder' => 'Thread URL',
		))),
			'afterhint' => '<b>Note</b>:  Must be thread type "discussion" in order for the thread to be associated with this item',
			'_type' => 'option',
		));
		if ($__vars['category']->{'thread_node_id'} AND $__vars['category']->{'ThreadForum'}) {
			$__compilerTemp2[] = array(
				'value' => 'create',
				'label' => 'Create a new discussion thread',
				'hint' => '',
				'_type' => 'option',
			);
		}
		$__compilerTemp1 .= $__templater->formRadioRow(array(
			'name' => 'thread_action',
			'value' => 'update',
		), $__compilerTemp2, array(
			'label' => 'Action',
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
		'action' => $__templater->func('link', array('showcase/change-thread', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);