<?php
// FROM HASH: 6a5cec1d44f3259bf768f6408bf5cf60
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit post');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['thread'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['post'], 'isFirstPost', array()) AND $__templater->method($__vars['thread'], 'canEdit', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formPrefixInputRow(($__templater->method($__vars['thread'], 'isPrefixEditable', array()) ? $__vars['prefixes'] : array()), array(
			'type' => 'thread',
			'prefix-value' => $__vars['thread']['prefix_id'],
			'textbox-value' => $__vars['thread']['title'],
			'placeholder' => 'Title' . $__vars['xf']['language']['ellipsis'],
			'maxlength' => $__templater->func('max_length', array($__vars['thread'], 'title', ), false),
			'help-href' => $__templater->func('link', array('forums/prefix-help', $__vars['forum'], ), false),
		), array(
			'label' => 'Title',
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp2 .= '
					' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
				';
	}
	$__compilerTemp3 = '';
	if ((($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('fs_bunny', 'allow', )) AND ($__vars['xf']['options']['fs_bi_accessKey'] AND $__vars['xf']['options']['fs_bi_libraryId']))) AND $__templater->method($__vars['post'], 'isFirstPost', array())) {
		$__compilerTemp3 .= '

	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
';
		$__templater->inlineJs('
	$(document).ready(function() {
		
			$("#bunnyVideoBtn").click(function () {
			
				$(\'input[name="isBunnyUpload"]\').val("1");
			});
			 $("#attachVideoBtn").click(function () {
				
			  $(\'input[name="isBunnyUpload"]\').val("0");
			});
	});
');
		$__compilerTemp3 .= '
	
	' . $__templater->callMacro('bunny_helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
			'forceHash' => $__vars['forum']['draft_thread']['attachment_hash'],
		), $__vars) . '
	
';
	}
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['post'], 'isFirstPost', array()) AND $__templater->method($__vars['thread'], 'canEdit', array())) {
		$__compilerTemp4 .= '
				';
		$__compilerTemp5 = '';
		$__compilerTemp5 .= '
						' . $__templater->filter($__templater->method($__vars['thread']['TypeHandler'], 'renderExtraDataEdit', array($__vars['thread'], 'edit', ($__vars['quickEdit'] ? 'first_post_quick' : 'first_post'), )), array(array('raw', array()),), true) . '
					';
		if (strlen(trim($__compilerTemp5)) > 0) {
			$__compilerTemp4 .= '
					';
			if (!$__vars['quickEdit']) {
				$__compilerTemp4 .= '
						<hr class="formRowSep" />
					';
			}
			$__compilerTemp4 .= '
					' . $__compilerTemp5 . '
				';
		}
		$__compilerTemp4 .= '

				';
		$__compilerTemp6 = '';
		$__compilerTemp6 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'threads',
			'set' => $__vars['thread']['custom_fields'],
			'editMode' => $__templater->method($__vars['thread'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['forum']['field_cache'],
			'rowType' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp6)) > 0) {
			$__compilerTemp4 .= '
					<hr class="formRowSep" />
					' . $__compilerTemp6 . '
				';
		}
		$__compilerTemp4 .= '
			';
	}
	$__compilerTemp7 = '';
	if ($__templater->method($__vars['post'], 'canEditSilently', array())) {
		$__compilerTemp7 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'edit_type', array(
			'canEditSilently' => $__templater->method($__vars['post'], 'canEditSilently', array()),
		), $__vars) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp8 = '';
	if ($__templater->method($__vars['post'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp8 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp9 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp9 .= '
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
			' . $__compilerTemp1 . '

			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['post']['message'],
		'attachments' => $__vars['attachmentData']['attachments'],
		'data-min-height' => ($__vars['quickEdit'] ? 100 : ''),
		'data-preview-url' => $__templater->func('link', array('posts/preview', $__vars['post'], ), false),
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Message',
	)) . '

			' . $__templater->formRow('
				' . $__compilerTemp2 . '
' . $__compilerTemp3 . '

			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel mergePrev' : ''),
	)) . '

			' . $__compilerTemp4 . '

			' . $__compilerTemp7 . '

			' . $__compilerTemp8 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
		'html' => '
				' . $__compilerTemp9 . '
			',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('posts/edit', $__vars['post'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager' . ((($__templater->method($__vars['post'], 'isFirstPost', array()) AND $__templater->method($__vars['thread'], 'canEdit', array()))) ? ' post-edit' : ''),
	));
	return $__finalCompiled;
}
);