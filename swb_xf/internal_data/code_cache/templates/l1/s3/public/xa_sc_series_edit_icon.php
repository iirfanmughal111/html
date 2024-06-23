<?php
// FROM HASH: 7429d8c1990eab28cb40a88a2c0d538b
return array(
'macros' => array('icon_edit' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			<span class="contentRow-figureIcon">' . $__templater->func('sc_series_icon', array($__vars['series'], 'm', ), true) . '</span>
		</div>
		<div class="contentRow-main">
			';
	if ($__vars['series']['icon_date']) {
		$__finalCompiled .= '
				';
		$__compilerTemp1 = array(array(
			'value' => 'custom',
			'label' => 'Upload custom icon' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->callMacro(null, 'custom_dependent', array(), $__vars)),
			'_type' => 'option',
		));
		if ($__vars['series']['icon_date']) {
			$__compilerTemp1[] = array(
				'value' => 'delete',
				'label' => 'Delete current icon',
				'_type' => 'option',
			);
		}
		$__finalCompiled .= $__templater->formRadio(array(
			'name' => 'icon_action',
			'value' => 'custom',
		), $__compilerTemp1) . '
			';
	} else {
		$__finalCompiled .= '
				';
		$__vars['uploadId'] = $__templater->func('unique_id', array(), false);
		$__finalCompiled .= '
				<label for="' . $__templater->escape($__vars['uploadId']) . '">' . 'Upload new icon' . $__vars['xf']['language']['label_separator'] . '</label>
				' . $__templater->callMacro(null, 'custom_dependent', array(
			'id' => $__vars['uploadId'],
		), $__vars) . '
				' . $__templater->formHiddenVal('icon_action', 'custom', array(
		)) . '
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'custom_dependent' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'id' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formUpload(array(
		'name' => 'upload',
		'accept' => '.gif,.jpeg,.jpg,.jpe,.png',
		'id' => $__vars['id'],
	)) . '
	<dfn class="inputChoices-explain">
		' . 'It is recommended that you use an image that is at least ' . 200 . 'x' . 200 . ' pixels.' . '
	</dfn>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Series icon');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['series'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body block-row">
			' . $__templater->callMacro(null, 'icon_edit', array(
		'series' => $__vars['series'],
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/series/edit-icon', $__vars['series'], ), false),
		'upload' => 'true',
		'ajax' => 'true',
		'class' => 'block',
	)) . '

' . '

';
	return $__finalCompiled;
}
);