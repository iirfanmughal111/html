<?php
// FROM HASH: d8be602338beeb0274505f4cb1c399dd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Your account');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Your licenses', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/licenses', $__vars['xf']['visitor'], ), false),
		'icon' => 'download',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		' . $__templater->callMacro('dbtech_ecommerce_account_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'dbtech-ecommerce/account',
		'addressFilter' => $__vars['addressFilter'],
		'stateFilter' => $__vars['stateFilter'],
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['orders'], 'empty', array())) {
		$__finalCompiled .= '
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['orders'])) {
			foreach ($__vars['orders'] AS $__vars['order']) {
				$__compilerTemp1 .= '
						' . $__templater->callMacro('dbtech_ecommerce_order_list_macros', 'order', array(
					'order' => $__vars['order'],
					'linkPrefix' => 'dbtech-ecommerce/account/order',
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . 'There are no orders matching your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No orders have been placed yet.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/account',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);