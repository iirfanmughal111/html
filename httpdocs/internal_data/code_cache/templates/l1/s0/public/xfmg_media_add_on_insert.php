<?php
// FROM HASH: 5b5ab8c41c3b0006ca70cdd37fd3df0d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
		' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'xfmgMediaFields',
		'set' => $__vars['mediaItem']['custom_fields'],
		'namePrefix' => $__vars['namePrefix'] . '[custom_fields]',
		'rowType' => 'fullWidth noGutter',
		'rowClass' => 'mediaItem-input',
		'editMode' => $__templater->method($__vars['mediaItem'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['container']['field_cache'],
		'additionalFilters' => array('display_add_media', ),
	), $__vars) . '
		';
	if ($__templater->method($__vars['container'], 'canEditTags', array())) {
		$__compilerTemp1 .= '
			';
		$__compilerTemp2 = '';
		if ($__vars['container']['min_tags']) {
			$__compilerTemp2 .= '
						' . 'This content must have at least ' . $__templater->escape($__vars['container']['min_tags']) . ' tag(s).' . '
					';
		}
		$__compilerTemp1 .= $__templater->formTokenInputRow(array(
			'name' => $__vars['namePrefix'] . '[tags]',
			'href' => $__templater->func('link', array('misc/tag-auto-complete', ), false),
		), array(
			'rowclass' => 'mediaItem-input',
			'rowtype' => 'fullWidth noGutter',
			'label' => 'Tags',
			'explain' => '
					' . 'Multiple tags may be separated by commas.' . '
					' . $__compilerTemp2 . '
				',
		)) . '
		';
	}
	$__compilerTemp1 .= '
	';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	' . $__compilerTemp1 . '
';
	} else {
		$__finalCompiled .= '
	<!-- no content to display but suppress any no content errors -->
';
	}
	return $__finalCompiled;
}
);