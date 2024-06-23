<?php
// FROM HASH: 6106fab736d6926e3ca5aabf4ba33a92
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Delete products');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['products'])) {
		foreach ($__vars['products'] AS $__vars['product']) {
			$__compilerTemp1 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['product']['product_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('Are you sure you want to delete ' . $__templater->escape($__vars['total']) . ' product(s)?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__vars['canHardDelete'],
	), $__vars) . '

			' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
	)) . '
	</div>

	' . $__compilerTemp1 . '

	' . $__templater->formHiddenVal('type', 'dbtech_ecommerce_product', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'delete', array(
	)) . '
	' . $__templater->formHiddenVal('confirmed', '1', array(
	)) . '

	' . $__templater->func('redirect_input', array($__vars['redirect'], null, true)) . '
', array(
		'action' => $__templater->func('link', array('inline-mod', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);