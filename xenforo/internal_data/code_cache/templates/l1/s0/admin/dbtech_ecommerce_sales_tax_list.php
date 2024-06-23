<?php
// FROM HASH: 0bf9f4c232f8abec0ce987196d25bfc8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Sales tax');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add sales tax rate', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/sales-tax/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['countries'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'dbtech-ecommerce/sales-tax',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['countries'])) {
			foreach ($__vars['countries'] AS $__vars['country']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
				), array(array(
					'hash' => $__vars['country']['country_code'],
					'href' => $__templater->func('link', array('dbtech-ecommerce/sales-tax/edit', $__vars['country'], ), false),
					'label' => $__templater->escape($__vars['country']['name']),
					'hint' => $__templater->func('number', array($__vars['country']['sales_tax_rate'], 3, ), true) . '%',
					'explain' => $__templater->escape($__vars['country']['native_name']),
					'_type' => 'main',
					'html' => '',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['countries'], ), true) . '</span>
			</div>
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'There are no countries with a sales tax rate set.' . '</div>
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('option_macros', 'option_form_block', array(
		'options' => $__vars['options'],
	), $__vars);
	return $__finalCompiled;
}
);