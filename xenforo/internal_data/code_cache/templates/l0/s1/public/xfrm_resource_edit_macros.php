<?php
// FROM HASH: ce5fbe8a11a14088e9327392ee29b513
return array(
'macros' => array('title' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
		'prefixes' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formPrefixInputRow($__vars['prefixes'], array(
		'type' => 'resource',
		'prefix-value' => $__vars['resource']['prefix_id'],
		'textbox-value' => $__vars['resource']['title_'],
		'maxlength' => $__templater->func('max_length', array($__vars['resource'], 'title', ), false),
		'placeholder' => 'Title' . $__vars['xf']['language']['ellipsis'],
		'help-href' => $__templater->func('link', array('resources/categories/prefix-help', $__vars['resource'], ), false),
	), array(
		'label' => 'Title',
	)) . '
';
	return $__finalCompiled;
}
),
'tag_line' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
		'name' => 'tag_line',
		'value' => $__vars['resource']['tag_line_'],
		'maxlength' => $__templater->func('max_length', array($__vars['resource'], 'tag_line', ), false),
	), array(
		'label' => 'Tag line',
		'explain' => 'Provide a very brief, one-line description of your resource.',
	)) . '
';
	return $__finalCompiled;
}
),
'type' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'currentType' => '!',
		'resource' => '!',
		'category' => '!',
		'versionAttachData' => '!',
		'allowCurrentType' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = array();
	if ($__vars['category']['allow_local'] OR (($__vars['currentType'] == 'download_local') AND $__vars['allowCurrentType'])) {
		$__compilerTemp1[] = array(
			'value' => 'download_local',
			'label' => 'Uploaded files' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['versionAttachData'],
			'hiddenName' => 'version_attachment_hash',
		), $__vars)),
			'_type' => 'option',
		);
	}
	if ($__vars['category']['allow_external'] OR (($__vars['currentType'] == 'download_external') AND $__vars['allowCurrentType'])) {
		$__compilerTemp1[] = array(
			'value' => 'download_external',
			'label' => 'External download URL' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->formTextBox(array(
			'name' => 'external_download_url',
			'value' => $__vars['category']['draft_resource']['external_download_url'],
			'maxlength' => $__templater->func('max_length', array('XFRM:ResourceVersion', 'download_url', ), false),
		))),
			'_type' => 'option',
		);
	}
	if ($__vars['category']['allow_commercial_external'] OR (($__vars['currentType'] == 'external_purchase') AND $__vars['allowCurrentType'])) {
		$__compilerTemp1[] = array(
			'value' => 'external_purchase',
			'label' => 'External purchase' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->callMacro('xfrm_resource_edit_macros', 'purchase_inputs', array(
			'resource' => $__vars['resource'],
			'withUrl' => true,
		), $__vars)),
			'_type' => 'option',
		);
	}
	if ($__vars['category']['allow_fileless'] OR (($__vars['currentType'] == 'fileless') AND $__vars['allowCurrentType'])) {
		$__compilerTemp1[] = array(
			'value' => 'fileless',
			'label' => 'Does not have a file',
			'hint' => 'The description will contain the contents of this resource.',
			'_type' => 'option',
		);
	}
	$__finalCompiled .= $__templater->formRadioRow(array(
		'name' => 'resource_type',
		'value' => $__vars['currentType'],
		'data-xf-init' => 'attachment-manager',
	), $__compilerTemp1, array(
		'label' => 'Type',
	)) . '
';
	return $__finalCompiled;
}
),
'purchase_inputs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
		'withUrl' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="inputGroup">
		';
	if ($__vars['withUrl']) {
		$__finalCompiled .= '
			' . $__templater->formTextBox(array(
			'name' => 'external_purchase_url',
			'value' => $__vars['resource']['external_purchase_url'],
			'placeholder' => 'External purchase URL',
			'aria-label' => $__templater->filter('External purchase URL', array(array('for_attr', array()),), false),
		)) . '
			<span class="inputGroup-splitter"></span>
		';
	}
	$__finalCompiled .= '
		' . $__templater->formTextBox(array(
		'name' => 'price',
		'value' => ($__vars['resource']['price'] ?: ''),
		'placeholder' => 'Price',
		'aria-label' => $__templater->filter('Price', array(array('for_attr', array()),), false),
		'style' => 'width: 120px',
	)) . '
		<span class="inputGroup-splitter"></span>
		';
	$__compilerTemp1 = array();
	$__compilerTemp2 = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('XFRM:ResourceItem', )), 'getAvailableCurrencies', array($__vars['resource'], ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['currency']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['currency']['code'],
				'label' => $__templater->escape($__vars['currency']['code']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelect(array(
		'name' => 'currency',
		'value' => $__templater->filter($__vars['resource']['currency'], array(array('to_upper', array()),), false),
		'style' => 'width: 110px',
		'aria-label' => $__templater->filter('currency', array(array('for_attr', array()),), false),
	), $__compilerTemp1) . '
	</div>
';
	return $__finalCompiled;
}
),
'description' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'description' => '',
		'attachmentData' => array(),
		'previewUrl' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div data-xf-init="attachment-manager">
		' . $__templater->formEditorRow(array(
		'name' => 'description',
		'value' => $__vars['description'],
		'data-min-height' => '200',
		'attachments' => $__vars['attachmentData']['attachments'],
		'data-preview-url' => $__vars['previewUrl'],
	), array(
		'label' => 'Description',
	)) . '

		';
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->formRow('
			' . $__compilerTemp1 . '
		', array(
	)) . '
	</div>
';
	return $__finalCompiled;
}
),
'external_url' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
		'name' => 'external_url',
		'value' => $__vars['resource']['external_url_'],
		'maxlength' => $__templater->func('max_length', array($__vars['resource'], 'external_url', ), false),
	), array(
		'label' => 'Additional information URL',
	)) . '
';
	return $__finalCompiled;
}
),
'alt_support_url' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['resource']['Category']['enable_support_url']) {
		$__finalCompiled .= '
		' . $__templater->formTextBoxRow(array(
			'name' => 'alt_support_url',
			'value' => $__vars['resource']['alt_support_url_'],
			'maxlength' => $__templater->func('max_length', array($__vars['resource'], 'alt_support_url', ), false),
		), array(
			'label' => 'Alternative support URL',
			'explain' => 'If you have a specific location where you will be providing support or answering questions, please enter the URL here.',
		)) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'resource_icon' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->method($__vars['resource'], 'canEditIcon', array())) {
		$__finalCompiled .= '
		' . $__templater->formRow('
			' . $__templater->callMacro('xfrm_resource_edit_icon', 'icon_edit', array(
			'resource' => $__vars['resource'],
		), $__vars) . '
		', array(
			'rowclass' => 'noColon',
		)) . '
	';
	}
	$__finalCompiled .= '
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