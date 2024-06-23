<?php
// FROM HASH: 5aecf90d17064af324a09afbf2727bdd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reassign page');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['from_page_management']) {
		$__compilerTemp1 .= '
				' . $__templater->formHiddenVal('mp', true, array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'ac' => 'single',
		'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'username', ), false),
	), array(
		'label' => 'New owner',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'alert',
		'selected' => true,
		'label' => 'Notify the current and new owners of this action.' . ' ' . 'Reason' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'alert_reason',
		'placeholder' => 'Optional',
		'maxlength' => '250',
	))),
		'_type' => 'option',
	)), array(
	)) . '
			
			' . $__compilerTemp1 . '			
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/page/reassign', $__vars['page'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);