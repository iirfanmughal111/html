<?php
// FROM HASH: 4e1af24a5d3043ccf076e91c1379d4be
return array(
'macros' => array('title_item_add' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'prefixes' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formPrefixInputRow($__vars['prefixes'], array(
		'type' => 'sc_item',
		'prefix-value' => ($__vars['item']['Category']['draft_item']['prefix_id'] ?: ($__vars['item']['prefix_id'] ?: $__vars['item']['Category']['default_prefix_id'])),
		'textbox-value' => $__vars['item']['title_'],
		'textbox-class' => 'input--title',
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'title', ), false),
		'placeholder' => 'Title' . $__vars['xf']['language']['ellipsis'],
		'help-href' => $__templater->func('link', array('showcase/categories/prefix-help', $__vars['item'], ), false),
	), array(
		'label' => 'Title',
		'rowtype' => 'fullWidth noLabel',
	)) . '
';
	return $__finalCompiled;
}
),
'title_item_edit' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'prefixes' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formPrefixInputRow($__vars['prefixes'], array(
		'type' => 'sc_item',
		'prefix-value' => $__vars['item']['prefix_id'],
		'textbox-value' => $__vars['item']['title_'],
		'textbox-class' => 'input--title',
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'title', ), false),
		'placeholder' => 'Title' . $__vars['xf']['language']['ellipsis'],
		'help-href' => $__templater->func('link', array('showcase/categories/prefix-help', $__vars['item'], ), false),
	), array(
		'label' => 'Title',
		'rowtype' => 'fullWidth noLabel',
	)) . '
';
	return $__finalCompiled;
}
),
'message' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'message' => '',
		'minLength' => false,
		'attachmentData' => array(),
		'label' => '',
		'description' => '',
		'showAttachmentRequired' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div data-xf-init="attachment-manager">
		';
	if ($__vars['item']['Category']['editor_s1']) {
		$__finalCompiled .= '
			';
		$__compilerTemp1 = '';
		if ($__vars['minLength']) {
			$__compilerTemp1 .= '	
						<br><span style="font-weight: 700; color:red;">' . 'This section must contain at least ' . $__templater->escape($__vars['minLength']) . ' characters.' . '</span>
					';
		}
		$__finalCompiled .= $__templater->formEditorRow(array(
			'name' => 'message',
			'value' => $__vars['message'],
			'data-min-height' => '250',
			'attachments' => $__vars['attachmentData']['attachments'],
		), array(
			'rowtype' => '',
			'label' => $__templater->escape($__vars['label']),
			'hint' => ($__vars['minLength'] ? 'Required' : 'Optional'),
			'explain' => '
					' . $__templater->filter($__vars['description'], array(array('raw', array()),), true) . '

					' . $__compilerTemp1 . '
				',
		)) . '
		';
	}
	$__finalCompiled .= '

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
		';
	if ($__vars['attachmentData'] AND ($__vars['item']['Category']['require_item_image'] AND $__vars['showAttachmentRequired'])) {
		$__finalCompiled .= '		
			' . $__templater->formRow('
				' . 'You must upload at least 1 image attachment.' . '
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
'message_section' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'message' => '',
		'minLength' => false,
		'section' => '',
		'label' => '',
		'description' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if ($__vars['minLength']) {
		$__compilerTemp1 .= '	
				<br><span style="font-weight: 700; color:red;">' . 'This section must contain at least ' . $__templater->escape($__vars['minLength']) . ' characters.' . '</span>
			';
	}
	$__finalCompiled .= $__templater->formEditorRow(array(
		'name' => 'message_' . $__vars['section'],
		'value' => $__vars['message'],
		'data-min-height' => '150',
	), array(
		'rowtype' => '',
		'label' => $__templater->escape($__vars['label']),
		'hint' => ($__vars['minLength'] ? 'Required' : 'Optional'),
		'explain' => '
			' . $__templater->filter($__vars['description'], array(array('raw', array()),), true) . '

			' . $__compilerTemp1 . '
		',
	)) . '
';
	return $__finalCompiled;
}
),
'og_title_meta_title' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
		'name' => 'og_title',
		'value' => $__vars['item']['og_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'og_title', ), false),
	), array(
		'label' => 'OG title',
		'hint' => 'Optional',
		'explain' => 'The Open Graph / Twitter title used on social media (Facebook, Twitter etc).',
	)) . '

	' . $__templater->formTextBoxRow(array(
		'name' => 'meta_title',
		'value' => $__vars['item']['meta_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'meta_title', ), false),
	), array(
		'label' => 'Meta title',
		'hint' => 'Optional',
		'explain' => 'The title used in the title tag. A meta title, also known as a title tag, refers to the text that is displayed on search engine result pages and browser tabs to indicate the topic of a webpage. ',
	)) . '
';
	return $__finalCompiled;
}
),
'description' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['item']['description_'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'description', ), false),
	), array(
		'label' => 'Description',
		'explain' => 'Optional:  Provide a very brief description of your item',
	)) . '
';
	return $__finalCompiled;
}
),
'meta_description' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextAreaRow(array(
		'name' => 'meta_description',
		'value' => $__vars['item']['meta_description_'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'meta_description', ), false),
	), array(
		'label' => 'Meta description',
		'hint' => 'Optional',
		'explain' => 'Provide a brief summary of your item for search engines.
<br><br>
A meta description can influence the decision of the searcher as to whether they want to click through on your item from search results or not. The more descriptive, attractive and relevant the description, the more likely someone will click through.',
	)) . '
';
	return $__finalCompiled;
}
),
'location' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
		'name' => 'location',
		'value' => $__vars['item']['location_'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'location', ), false),
	), array(
		'label' => 'Location',
		'hint' => ($__vars['item']['Category']['require_location'] ? 'Required' : 'Optional'),
		'explain' => ($__vars['item']['Category']['require_location'] ? 'Enter the address of the location associated with your item.<br>
eg 1600 Amphitheatre Parkway, Mountain View, CA 94043' : 'Enter the address of the location associated with your item if you wish to link to a google map. <br>
eg 1600 Amphitheatre Parkway, Mountain View, CA 94043'),
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

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);