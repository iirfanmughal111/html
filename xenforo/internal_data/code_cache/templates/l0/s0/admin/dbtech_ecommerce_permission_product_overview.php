<?php
// FROM HASH: 4d20506be69cdb804586c75568dc902a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('eCommerce product permissions');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['products'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'dbtech-ecommerce-products',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['products'])) {
			foreach ($__vars['products'] AS $__vars['product']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
					'rowclass' => ($__vars['customPermissions'][$__vars['product']['product_id']] ? 'dataList-row--custom' : ''),
				), array(array(
					'class' => 'dataList-cell--link dataList-cell--main',
					'hash' => $__vars['product']['product_id'],
					'_type' => 'cell',
					'html' => '
								<a href="' . $__templater->func('link', array('permissions/dbtech-ecommerce-products', $__vars['product'], ), true) . '">
									<div class="dataList-mainRow">' . $__templater->escape($__vars['product']['full_title']) . '</div>
								</a>
							',
				),
				array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/products/edit', $__vars['product'], ), false),
					'_type' => 'action',
					'html' => 'Edit',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
			</div>
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	return $__finalCompiled;
}
);