<?php
// FROM HASH: 676935cf155935ec4be0bc11fe432d38
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add download');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
		'productId' => null,
		'downloadsOnly' => true,
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Proceed' . $__vars['xf']['language']['ellipsis'],
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