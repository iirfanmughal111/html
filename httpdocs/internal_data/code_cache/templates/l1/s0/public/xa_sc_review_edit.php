<?php
// FROM HASH: 5a447465bd7c003c0919f7a2797c2bd6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit review');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_reviews',
		'set' => $__vars['review']['custom_fields'],
		'group' => 'top',
		'editMode' => $__templater->method($__vars['review'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['review_field_cache'],
		'rowType' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
				' . $__compilerTemp2 . '
			';
	}
	$__compilerTemp3 = '';
	if ($__vars['category']['allow_pros_cons']) {
		$__compilerTemp3 .= '
				' . $__templater->formTextAreaRow(array(
			'name' => 'pros',
			'value' => $__vars['review']['pros'],
			'rows' => '2',
			'autosize' => 'true',
			'data-xf-init' => 'min-length',
			'data-min-length' => $__vars['xf']['options']['xaScMinProsLength'],
			'data-allow-empty' => 'true',
			'data-toggle-target' => '#js-itemProsLength',
			'maxlength' => $__vars['xf']['options']['xaScMaxProsLength'],
		), array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
			'label' => 'Pros',
		)) . '

				' . $__templater->formTextAreaRow(array(
			'name' => 'cons',
			'value' => $__vars['review']['cons'],
			'rows' => '2',
			'autosize' => 'true',
			'data-xf-init' => 'min-length',
			'data-min-length' => $__vars['xf']['options']['xaScMinConsLength'],
			'data-allow-empty' => 'true',
			'data-toggle-target' => '#js-itemConsLength',
			'maxlength' => $__vars['xf']['options']['xaScMaxConsLength'],
		), array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
			'label' => 'Cons',
		)) . '
			';
	}
	$__compilerTemp4 = '';
	$__compilerTemp5 = '';
	$__compilerTemp5 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_reviews',
		'set' => $__vars['review']['custom_fields'],
		'group' => 'middle',
		'editMode' => $__templater->method($__vars['review'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['review_field_cache'],
		'rowType' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp5)) > 0) {
		$__compilerTemp4 .= '
				' . $__compilerTemp5 . '
			';
	}
	$__compilerTemp6 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp6 .= '
						' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
					';
	}
	$__compilerTemp7 = '';
	$__compilerTemp8 = '';
	$__compilerTemp8 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_reviews',
		'set' => $__vars['review']['custom_fields'],
		'group' => 'bottom',
		'editMode' => $__templater->method($__vars['review'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['review_field_cache'],
		'rowType' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp8)) > 0) {
		$__compilerTemp7 .= '
				' . $__compilerTemp8 . '
			';
	}
	$__compilerTemp9 = '';
	$__compilerTemp10 = '';
	$__compilerTemp10 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_reviews',
		'set' => $__vars['review']['custom_fields'],
		'group' => 'self_place',
		'editMode' => $__templater->method($__vars['review'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['review_field_cache'],
		'rowType' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp10)) > 0) {
		$__compilerTemp9 .= '
				' . $__compilerTemp10 . '
			';
	}
	$__compilerTemp11 = '';
	if ($__templater->method($__vars['review'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp11 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp12 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp12 .= '
					' . $__templater->button('Cancel', array(
			'class' => 'js-cancelButton',
			'icon' => 'cancel',
		), '', array(
		)) . '
				';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			<span class="u-anchorTarget js-editContainer"></span>
			
			' . $__templater->callMacro('rating_macros', 'rating', array(
		'currentRating' => $__vars['review']['rating'],
		'rowType' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	), $__vars) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['review']['title_'],
		'maxlength' => $__templater->func('max_length', array($__vars['review'], 'title', ), false),
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
		'label' => 'Review title',
		'hint' => ($__vars['xf']['options']['xaScRequireReviewTitle'] ? 'Required' : 'Optional'),
		'explain' => ($__vars['xf']['options']['xaScRequireReviewTitle'] ? 'The review title should be a summarised view of the entire review with 100 characters or less.  
<br>
<b>A Review title is required when submitting a review.</b>' : 'The <b>optional</b> review title should be a summarized view of the entire review with 100 characters or less. '),
	)) . '
			
			' . $__compilerTemp1 . '
			
			' . $__compilerTemp3 . '
			
			' . $__compilerTemp4 . '

			<div data-xf-init="attachment-manager">
				' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['review']['message'],
		'data-min-height' => '100',
		'attachments' => $__vars['attachmentData']['attachments'],
		'data-preview-url' => $__templater->func('link', array('showcase/review/preview', $__vars['review'], ), false),
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
		'label' => 'Review',
		'hint' => ($__vars['category']['require_review'] ? 'Required' : ''),
		'explain' => 'Explain why you\'re giving this rating. Reviews which are not constructive may be removed without notice.',
	)) . '

				' . $__templater->formRow('
					' . $__compilerTemp6 . '
				', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '
			</div>

			' . $__compilerTemp7 . '

			' . $__compilerTemp9 . '

			' . $__templater->formRow('
				' . $__templater->callMacro('helper_action', 'edit_type', array(
		'canEditSilently' => $__templater->method($__vars['review'], 'canEditSilently', array()),
	), $__vars) . '
			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '

			' . $__compilerTemp11 . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
		'html' => '
				' . $__compilerTemp12 . '
			',
	)) . '			
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/review/edit', $__vars['review'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);