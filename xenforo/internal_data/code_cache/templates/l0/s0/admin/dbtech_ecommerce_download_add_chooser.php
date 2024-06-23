<?php
// FROM HASH: f3c9dbc60a54cdb28754013e5a8b6b02
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add download to' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['handlers']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_edit', array(
		'productId' => $__vars['product']['product_id'],
		'downloadsOnly' => true,
	), $__vars) . '

			' . $__templater->formRadioRow(array(
		'name' => 'download_type',
	), $__compilerTemp1, array(
		'label' => 'Download type',
	)) . '
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'add',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/downloads/add', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);