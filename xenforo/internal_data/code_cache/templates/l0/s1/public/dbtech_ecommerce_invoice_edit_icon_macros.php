<?php
// FROM HASH: 36028d4cc047ec94f76b25e62344039c
return array(
'macros' => array('edit_icon' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'context' => 'public',
		'linkPrefix' => 'dbtech-ecommerce',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if ($__vars['xf']['options']['dbtechEcommerceInvoiceIconPath']) {
		$__compilerTemp1 .= '
							';
		$__compilerTemp2 = array(array(
			'value' => 'custom',
			'label' => 'Upload a custom logo' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array($__templater->callMacro(null, 'custom_dependent', array(), $__vars)),
			'_type' => 'option',
		));
		if ($__vars['xf']['options']['dbtechEcommerceInvoiceIconPath']) {
			$__compilerTemp2[] = array(
				'value' => 'delete',
				'label' => 'Delete the current logo',
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
							<span>' . 'Upload a new logo' . $__vars['xf']['language']['label_separator'] . '</span>
							' . $__templater->callMacro(null, 'custom_dependent', array(), $__vars) . '
							' . $__templater->formHiddenVal('icon_action', 'custom', array(
		)) . '
						';
	}
	$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<div class="block-body block-row">
				<div class="contentRow">
					<div class="contentRow-main">
						' . $__templater->func('dbtech_ecommerce_invoice_icon', array(), true) . '
					</div>
				</div>
				<div class="contentRow">
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
		'action' => $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/edit-icon', ), false),
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
		'accept' => '.png',
	)) . '
	<dfn class="inputChoices-explain">
		' . 'It is recommended that you use an image that is at least ' . 300 . 'x' . 130 . ' pixels.' . '
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