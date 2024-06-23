<?php
// FROM HASH: 776eab78ae7905f073242ce9411423f7
return array(
'macros' => array('message' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'message' => '',
		'attachmentData' => array(),
		'showAttachmentRequired' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div data-xf-init="attachment-manager">
		' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['message'],
		'data-min-height' => '250',
		'attachments' => $__vars['attachmentData']['attachments'],
	), array(
		'rowtype' => 'fullWidth',
		'label' => 'Series details',
	)) . '

		';
	if ($__vars['attachmentData']) {
		$__finalCompiled .= '
			' . $__templater->formRow('
				' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
			', array(
		)) . '
		';
	}
	$__finalCompiled .= '
	</div>
';
	return $__finalCompiled;
}
),
'description' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['series']['description_'],
		'autosize' => 'true',
		'maxlength' => $__templater->func('max_length', array($__vars['series'], 'description', ), false),
	), array(
		'label' => 'Description',
		'hint' => 'Optional',
	)) . '
';
	return $__finalCompiled;
}
),
'og_title_meta_title' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
		'name' => 'og_title',
		'value' => $__vars['series']['og_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['series'], 'og_title', ), false),
	), array(
		'label' => 'OG title',
		'hint' => 'Optional',
		'explain' => 'The Open Graph / Twitter title used on social media (Facebook, Twitter etc).',
	)) . '

	' . $__templater->formTextBoxRow(array(
		'name' => 'meta_title',
		'value' => $__vars['series']['meta_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['series'], 'meta_title', ), false),
	), array(
		'label' => 'Meta title',
		'hint' => 'Optional',
		'explain' => 'The title used in the title tag. A meta title, also known as a title tag, refers to the text that is displayed on search engine result pages and browser tabs to indicate the topic of a webpage. ',
	)) . '
';
	return $__finalCompiled;
}
),
'meta_description' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextAreaRow(array(
		'name' => 'meta_description',
		'value' => $__vars['series']['meta_description_'],
		'autosize' => 'true',
		'maxlength' => $__templater->func('max_length', array($__vars['series'], 'meta_description', ), false),
	), array(
		'label' => 'Meta description',
		'hint' => 'Optional',
		'explain' => 'Provide a brief summary of your series for search engines.
<br><br>
A meta description can influence the decision of the searcher as to whether they want to click through on your series from search results or not. The more descriptive, attractive and relevant the description, the more likely someone will click through.',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);