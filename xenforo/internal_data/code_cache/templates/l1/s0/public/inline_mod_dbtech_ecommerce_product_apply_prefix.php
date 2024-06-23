<?php
// FROM HASH: f1422718c0b8eae4daafd415d2863122
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Apply prefix');
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
			' . $__templater->formInfoRow('Are you sure you want to apply a prefix to ' . $__templater->escape($__vars['total']) . ' product(s)?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->callMacro('prefix_macros', 'row', array(
		'type' => 'dbtechEcommerceProduct',
		'prefixes' => $__vars['prefixes'],
		'selected' => $__vars['selectedPrefix'],
		'explain' => (($__vars['categoryCount'] > 1) ? 'The products you have selected are located in more than one category. Each product will only be updated if the chosen prefix is valid in its category.' : ''),
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'confirm',
	), array(
	)) . '
	</div>

	' . $__compilerTemp1 . '

	' . $__templater->formHiddenVal('type', 'dbtech_ecommerce_product', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'apply_prefix', array(
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