<?php
// FROM HASH: 033fa6c2dde753bf839a2335d8b91653
return array(
'macros' => array('edit_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'formUrl' => '!',
		'message' => '!',
		'attachmentData' => '!',
		'quickEdit' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
                    ' . $__templater->formRow('
                        ' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
                    ', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
                ';
	}
	$__compilerTemp2 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp2 .= '
                        ' . $__templater->button('Cancel', array(
			'class' => 'js-cancelButton',
		), '', array(
		)) . '
                    ';
	}
	$__finalCompiled .= $__templater->form('
        <div class="block-container">
            <div class="block-body">
                <span class="u-anchorTarget js-editContainer"></span>
                ' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['message'],
		'attachments' => $__vars['attachmentData']['attachments'],
		'data-min-height' => '100',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		'label' => 'Message',
	)) . '

                ' . $__compilerTemp1 . '
            </div>

            ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
		'html' => '
                    ' . $__compilerTemp2 . '
                ',
	)) . '
        </div>
    ', array(
		'action' => $__vars['formUrl'],
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit Comment');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['group'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro(null, 'edit_form', array(
		'formUrl' => $__templater->func('link', array('group-comments/edit', $__vars['comment'], ), false),
		'attachmentData' => $__vars['attachmentData'],
		'message' => $__vars['comment']['message'],
		'quickEdit' => $__vars['quickEdit'],
	), $__vars) . '

';
	return $__finalCompiled;
}
);