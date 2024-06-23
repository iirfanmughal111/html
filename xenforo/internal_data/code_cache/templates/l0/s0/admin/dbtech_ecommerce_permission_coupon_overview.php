<?php
// FROM HASH: 6357e30c4bc5faa4f8018160ad3bc0ef
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('eCommerce coupon permissions');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['coupons'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'dbtech-ecommerce-coupons',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['coupons'])) {
			foreach ($__vars['coupons'] AS $__vars['coupon']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
					'rowclass' => ($__vars['customPermissions'][$__vars['coupon']['coupon_id']] ? 'dataList-row--custom' : ''),
				), array(array(
					'class' => 'dataList-cell--link dataList-cell--main',
					'hash' => $__vars['coupon']['coupon_id'],
					'_type' => 'cell',
					'html' => '
								<a href="' . $__templater->func('link', array('permissions/dbtech-ecommerce-coupons', $__vars['coupon'], ), true) . '">
									<div class="dataList-mainRow">' . $__templater->escape($__vars['coupon']['title']) . '</div>
								</a>
							',
				),
				array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/coupons/edit', $__vars['coupon'], ), false),
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