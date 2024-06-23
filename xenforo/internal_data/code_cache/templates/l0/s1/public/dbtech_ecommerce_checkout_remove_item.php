<?php
// FROM HASH: 3fa5b79854fffcfff9d19a78456346fc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to remove this product from your cart?' . '
				<strong><a href="' . $__templater->func('link', array('dbtech-ecommerce/', $__vars['item']['Product'], ), true) . '">' . $__templater->escape($__vars['item']['Product']['full_title']) . '</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/checkout/remove-item', $__vars['item'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);