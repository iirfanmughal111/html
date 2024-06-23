<?php
// FROM HASH: 3746fb03e919c3152fcecfaaeec798a1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('xa_sc_edit_update_reply');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['itemUpdate']['Item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['reply'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp2 .= '
			' . $__templater->formRow('
				' . $__templater->button('', array(
			'type' => 'submit',
			'class' => 'button--primary',
			'icon' => 'save',
		), '', array(
		)) . '
				' . $__templater->button('Cancel', array(
			'class' => 'js-cancelButton',
		), '', array(
		)) . '
			', array(
			'rowtype' => 'fullWidth noLabel',
		)) . '
		';
	} else {
		$__compilerTemp2 .= '
			' . $__templater->formSubmitRow(array(
			'icon' => 'save',
		), array(
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="' . ((!$__vars['quickEdit']) ? 'block-container' : '') . '">
		<div class="' . ((!$__vars['quickEdit']) ? 'block-body' : '') . '">
			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['reply']['message'],
		'data-min-height' => ($__vars['quickEdit'] ? 40 : 100),
		'rendereropts' => array('treatAsStructuredText' => true, ),
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		'label' => 'Message',
	)) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__compilerTemp2 . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/update-reply/edit', $__vars['reply'], ), false),
		'class' => ((!$__vars['quickEdit']) ? 'block' : ''),
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);