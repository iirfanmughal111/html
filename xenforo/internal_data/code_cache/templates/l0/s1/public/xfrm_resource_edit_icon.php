<?php
// FROM HASH: 714c9a83fb455c32762f41e1cda28d8f
return array(
'macros' => array('icon_edit' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			<span class="contentRow-figureIcon">' . $__templater->func('resource_icon', array($__vars['resource'], 'm', ), true) . '</span>
		</div>
		<div class="contentRow-main">
			';
	if ($__vars['resource']['icon_date']) {
		$__finalCompiled .= '
				';
		$__compilerTemp1 = array(array(
			'value' => 'custom',
			'label' => 'Upload a custom icon' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->callMacro(null, 'custom_dependent', array(), $__vars)),
			'_type' => 'option',
		));
		if ($__vars['resource']['icon_date']) {
			$__compilerTemp1[] = array(
				'value' => 'delete',
				'label' => 'Delete the current icon',
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
				<label for="' . $__templater->escape($__vars['uploadId']) . '">' . 'Upload a new icon' . $__vars['xf']['language']['label_separator'] . '</label>
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
		' . 'It is recommended that you use an image that is at least ' . 100 . 'x' . 100 . ' pixels.' . '
	</dfn>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Resource icon');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body block-row">
			' . $__templater->callMacro(null, 'icon_edit', array(
		'resource' => $__vars['resource'],
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('resources/edit-icon', $__vars['resource'], ), false),
		'upload' => 'true',
		'ajax' => 'true',
		'class' => 'block',
	)) . '

' . '

';
	return $__finalCompiled;
}
);