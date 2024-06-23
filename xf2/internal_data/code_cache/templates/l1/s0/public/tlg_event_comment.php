<?php
// FROM HASH: ad9ffc79ffa71475b082a5e62787faeb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Post comment');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['selected'] = $__templater->preEscaped('events');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp2 .= '
                    ' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
			'forceHash' => $__vars['thread']['draft_reply']['attachment_hash'],
		), $__vars) . '
                ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['defaultMessage'],
		'attachments' => ($__vars['attachmentData'] ? $__vars['attachmentData']['attachments'] : array()),
		'placeholder' => 'Write your reply...',
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Message',
	)) . '

            ' . $__templater->formRow('
                ' . $__compilerTemp2 . '
            ', array(
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'reply',
		'submit' => 'Post comment',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('group-events/add-comment', $__vars['event'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-xf-init' => 'attachment-manager',
	));
	return $__finalCompiled;
}
);