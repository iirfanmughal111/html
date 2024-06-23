<?php
// FROM HASH: df12d8e84620cb7940684f185c5e0307
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add store credit');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'ac' => 'single',
	), array(
		'label' => 'User',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'store_credit_amount',
	), array(
		'label' => 'Store credit amount',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/store-credit/add', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);