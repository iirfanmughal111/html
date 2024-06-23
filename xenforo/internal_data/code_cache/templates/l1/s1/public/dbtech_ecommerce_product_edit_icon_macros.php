<?php
// FROM HASH: 08c9077d4845437b0f500b0734176e0e
return array(
'macros' => array('edit_icon' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'context' => 'public',
		'linkPrefix' => 'dbtech-ecommerce',
		'product' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if ($__vars['product']['icon_date']) {
		$__compilerTemp1 .= '
							';
		$__compilerTemp2 = array(array(
			'value' => 'custom',
			'label' => 'Upload a custom icon' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->callMacro(null, 'custom_dependent', array(), $__vars)),
			'_type' => 'option',
		));
		if ($__vars['product']['icon_date']) {
			$__compilerTemp2[] = array(
				'value' => 'delete',
				'label' => 'Delete the current icon',
				'_type' => 'option',
			);
		}
		$__compilerTemp1 .= $__templater->formRadio(array(
			'name' => 'icon_action',
			'value' => 'custom',
		), $__compilerTemp2) . '
						';
	} else {
		$__compilerTemp1 .= '
							<span>' . 'Upload a new icon' . $__vars['xf']['language']['label_separator'] . '</span>
							' . $__templater->callMacro(null, 'custom_dependent', array(), $__vars) . '
							' . $__templater->formHiddenVal('icon_action', 'custom', array(
		)) . '
						';
	}
	$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<div class="block-body block-row">
				<div class="contentRow">
					<div class="contentRow-figure">
						<span class="contentRow-figureIcon">' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['product'], 'm', ), true) . '</span>
					</div>
					<div class="contentRow-main">
						' . $__compilerTemp1 . '
					</div>
				</div>
			</div>
			' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
		</div>
	', array(
		'action' => $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/edit-icon', $__vars['product'], ), false),
		'upload' => 'true',
		'ajax' => 'true',
		'class' => 'block',
	)) . '
';
	return $__finalCompiled;
}
),
'custom_dependent' => array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formUpload(array(
		'name' => 'upload',
		'accept' => '.gif,.jpeg,.jpg,.jpe,.png,.svg',
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
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);