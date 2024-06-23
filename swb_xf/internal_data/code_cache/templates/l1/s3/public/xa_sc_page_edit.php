<?php
// FROM HASH: c983ee18d92a4d90937a4dc3475a0dec
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit page');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['page'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Manage pages'), $__templater->func('link', array('showcase/pages', $__vars['item'], ), false), array(
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
						' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
					';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['category'], 'canUploadAndManagePageAttachments', array())) {
		$__compilerTemp2 .= '
					' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'cover_image_above_page',
			'data-hide' => 'true',
			'label' => 'Display cover image above item page',
			'hint' => 'When enabled, will display the item page cover image (if set), above the item page.',
			'checked' => $__vars['page']['cover_image_above_page'],
			'_dependent' => array('
								' . $__templater->formTextAreaRow(array(
			'name' => 'cover_image_caption',
			'value' => $__vars['page']['cover_image_caption_'],
			'maxlength' => $__templater->func('max_length', array($__vars['page'], 'cover_image_caption', ), false),
		), array(
			'label' => 'Cover image caption',
			'rowtype' => 'fullWidth',
			'hint' => 'Optional',
			'explain' => 'Will display the item page cover image caption (if set), below the item page cover image that displays above the item page.',
		)) . '
							'),
			'_type' => 'option',
		))) . '
				';
	}
	$__compilerTemp3 = '';
	if ($__vars['page']['page_state'] == 'deleted') {
		$__compilerTemp3 .= '
				' . $__templater->formRadioRow(array(
			'name' => 'page_state',
			'value' => $__vars['page']['page_state'],
		), array(array(
			'value' => 'deleted',
			'label' => 'Deleted',
			'_type' => 'option',
		)), array(
			'label' => 'Status',
		)) . '
			';
	} else {
		$__compilerTemp3 .= '
				' . $__templater->formRadioRow(array(
			'name' => 'page_state',
			'value' => $__vars['page']['page_state'],
		), array(array(
			'value' => 'visible',
			'label' => 'Visible',
			'_type' => 'option',
		),
		array(
			'value' => 'draft',
			'label' => 'Draft',
			'_type' => 'option',
		)), array(
			'label' => 'Status',
		)) . '
			';
	}
	$__compilerTemp4 = '';
	if ($__vars['from_page_management']) {
		$__compilerTemp4 .= '
				' . $__templater->formHiddenVal('mp', true, array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['page']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['page'], 'title', ), false),
		'placeholder' => 'Title' . $__vars['xf']['language']['ellipsis'],
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Title',
	)) . '

			<div data-xf-init="attachment-manager">
				' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['page']['message_'],
		'data-min-height' => '250',
		'attachments' => $__vars['attachmentData']['attachments'],
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Showcase page',
	)) . '

				' . $__templater->formRow('
					' . $__compilerTemp1 . '
				', array(
	)) . '
			</div>

			<hr class="formRowSep" />

			' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['page']['description_'],
		'maxlength' => $__templater->func('max_length', array($__vars['page'], 'description', ), false),
	), array(
		'label' => 'Description',
		'hint' => 'Optional',
		'explain' => 'Provide a very brief description of this page.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formNumberBoxRow(array(
		'name' => 'display_order',
		'value' => $__vars['page']['display_order'],
		'min' => '1',
		'pattern' => '\\d*',
	), array(
		'label' => 'Display order',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'depth',
		'value' => $__vars['page']['depth'],
		'min' => '0',
		'pattern' => '\\d*',
	), array(
		'label' => 'Depth',
		'explain' => 'This optional setting is used to add indents to page titles in the multi-page navigation (to simulate hierarchy).',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				' . $__compilerTemp2 . '
				' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'display_byline',
		'label' => 'Display page byline',
		'hint' => 'When enabled, will display the item page Username/Author name and Create date below the Item Page Title.   <b>Note:</b>  <i>Should only be used in cases where the page author is not the item author. </i>',
		'checked' => $__vars['page']['display_byline'],
		'_type' => 'option',
	))) . '
			', array(
		'label' => 'Options',
	)) . '

			<hr class="formRowSep" />

			' . $__compilerTemp3 . '

			' . $__compilerTemp4 . '	
		</div>
		
		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Search engine optimization options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__templater->formTextBoxRow(array(
		'name' => 'og_title',
		'value' => $__vars['page']['og_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['page'], 'og_title', ), false),
	), array(
		'label' => 'OG title',
		'hint' => 'Optional',
		'explain' => 'The Open Graph / Twitter title used on social media (Facebook, Twitter etc).',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'meta_title',
		'value' => $__vars['page']['meta_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['page'], 'meta_title', ), false),
	), array(
		'label' => 'Meta title',
		'hint' => 'Optional',
		'explain' => 'The title used in the title tag. A meta title, also known as a title tag, refers to the text that is displayed on search engine result pages and browser tabs to indicate the topic of a webpage. ',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'meta_description',
		'value' => $__vars['page']['meta_description_'],
		'maxlength' => $__templater->func('max_length', array($__vars['page'], 'meta_description', ), false),
	), array(
		'label' => 'Meta description',
		'hint' => 'Optional',
		'explain' => 'Provide a brief summary of your item page for search engines.
<br><br>
A meta description can influence the decision of the searcher as to whether they want to click through on your item page from search results or not. The more descriptive, attractive and relevant the description, the more likely someone will click through.',
	)) . '
		</div>			

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/page/edit', $__vars['page'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-preview-url' => $__templater->func('link', array('showcase/page/preview', $__vars['page'], ), false),
	));
	return $__finalCompiled;
}
);