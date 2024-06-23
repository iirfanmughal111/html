<?php
// FROM HASH: 8613707ee36eee0b1c34628dcadce3d7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add page');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
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
			'label' => 'Display cover image above item page',
			'hint' => 'When enabled, will display the item page cover image (if set), above the item page.',
			'checked' => false,
			'_type' => 'option',
		))) . '
				';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => '',
		'maxlength' => $__templater->func('max_length', array($__vars['page'], 'title', ), false),
		'placeholder' => 'Title' . $__vars['xf']['language']['ellipsis'],
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Title',
	)) . '

			<div data-xf-init="attachment-manager">
				' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => '',
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
		'value' => '1',
		'min' => '1',
		'pattern' => '\\d*',
	), array(
		'label' => 'Display order',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'depth',
		'value' => '0',
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
		'_type' => 'option',
	))) . '
			', array(
		'label' => 'Options',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRadioRow(array(
		'name' => 'page_state',
		'value' => 'visible',
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
		'value' => $__vars['itemPage']['meta_description_'],
		'maxlength' => $__templater->func('max_length', array($__vars['itemPage'], 'meta_description', ), false),
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
		'action' => $__templater->func('link', array('showcase/add-page', $__vars['item'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-preview-url' => $__templater->func('link', array('showcase/page-preview', $__vars['item'], ), false),
	));
	return $__finalCompiled;
}
);