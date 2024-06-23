<?php
// FROM HASH: 395bd976c2ef4b3331ec9b8eb4aff59c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add license for' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_edit', array(
		'productId' => '',
		'licensesOnly' => true,
	), $__vars) . '
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'add',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/licenses/add', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);