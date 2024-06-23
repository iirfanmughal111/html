<?php
// FROM HASH: 7dec53c5302264b76d9adbd102b76726
return array(
'macros' => array('edit_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'context' => 'public',
		'linkPrefix' => '!',
		'download' => '!',
		'renderedOptions' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['download'], 'isInsert', array())) {
		$__compilerTemp1 .= '
			' . $__templater->formHiddenVal('download_type', $__vars['download']['download_type'], array(
		)) . '
		';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['download'], 'isUpdate', array()) AND $__templater->method($__vars['download'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp2 .= '
					' . $__templater->formRow('
						' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
					', array(
			'label' => 'Edit notice',
		)) . '

					<hr class="formRowSep" />
				';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['download'], 'isInsert', array())) {
		$__compilerTemp3 .= '
					' . $__templater->formRow('
						<div class="inputGroup">
							' . $__templater->formDateInput(array(
			'name' => 'date',
			'value' => ($__vars['download']['release_date'] ? $__templater->func('date', array($__vars['download']['release_date'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
		)) . '
							<span class="inputGroup-splitter"></span>
							' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'time',
			'value' => ($__vars['download']['release_date'] ? $__templater->func('date', array($__vars['download']['release_date'], 'H:i', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'H:i', ), false)),
		)) . '
						</div>
					', array(
			'label' => 'Release date',
			'rowtype' => 'input',
		)) . '
				';
	}
	$__finalCompiled .= $__templater->form('
		' . $__compilerTemp1 . '
		<div class="block-container">
			<div class="block-body">

				' . $__compilerTemp2 . '

				' . $__templater->formRow('
					' . $__templater->escape($__vars['download']['Product']['title']) . '
				', array(
		'label' => 'Product',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formTextBoxRow(array(
		'name' => 'version_string',
		'value' => $__vars['download']['version_string'],
		'maxlength' => $__templater->func('max_length', array('DBTech\\eCommerce:Download', 'version_string', ), false),
		'required' => 'true',
	), array(
		'label' => 'Version string',
		'explain' => '<b>Example:</b> v1.0.0.0',
	)) . '

				' . $__compilerTemp3 . '

				' . $__templater->formEditorRow(array(
		'name' => 'change_log',
		'value' => $__vars['download']['change_log'],
		'previewable' => '0',
	), array(
		'label' => 'Change log',
		'explain' => 'This will be displayed on the product information page.',
	)) . '

				' . $__templater->formCheckBoxRow(array(
		'listclass' => 'listInline',
	), array(array(
		'name' => 'has_new_features',
		'selected' => $__vars['download']['has_new_features'],
		'label' => 'New features',
		'_type' => 'option',
	),
	array(
		'name' => 'has_changed_features',
		'selected' => $__vars['download']['has_changed_features'],
		'label' => 'Changed features',
		'_type' => 'option',
	),
	array(
		'name' => 'has_bug_fixes',
		'selected' => $__vars['download']['has_bug_fixes'],
		'label' => 'Bug fixes',
		'_type' => 'option',
	),
	array(
		'name' => 'is_unstable',
		'selected' => $__vars['download']['is_unstable'],
		'label' => 'Unstable',
		'_type' => 'option',
	)), array(
		'label' => 'Release type',
	)) . '

				' . $__templater->formEditorRow(array(
		'name' => 'release_notes',
		'value' => $__vars['download']['release_notes'],
		'previewable' => '0',
	), array(
		'label' => 'Release notes',
		'explain' => 'If the product specifies an update notification forum, a thread will automatically be created containing the Change log specified above. This field can contain additional marketing-friendly text, and will be included above the change log in the release thread.<br />
If left blank, this download will not generate a release thread or post.',
	)) . '
			</div>

			' . $__templater->filter($__vars['renderedOptions'], array(array('raw', array()),), true) . '

			' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
		</div>
	', array(
		'action' => $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/save', $__vars['download'], array('product_id' => $__vars['download']['Product']['product_id'], ), ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
';
	return $__finalCompiled;
}
),
'reset_cache' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'context' => 'public',
		'linkPrefix' => '!',
		'download' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->form('
		<div class="block-container">
			<div class="block-body">
				' . $__templater->formInfoRow('
					' . 'Please confirm that you wish to reset the downloads cache for the following' . $__vars['xf']['language']['label_separator'] . '
					<strong><a href="' . $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/edit', $__vars['download'], ), true) . '">' . $__templater->escape($__vars['download']['title']) . '</a></strong>
				', array(
		'rowtype' => 'confirm',
	)) . '
			</div>

			' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
		</div>
	', array(
		'action' => $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/reset-cache', $__vars['download'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);