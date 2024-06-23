<?php
// FROM HASH: dd0ae906500c2ffd17130bc8383eb601
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Orders');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['criteria'], 'empty', array())) {
		$__compilerTemp1 .= '
		' . $__templater->button('Download as CSV', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/logs/orders/csv', null, array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ), ), false),
			'class' => 'button--cta',
			'icon' => 'download',
		), '', array(
		)) . '
	';
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__compilerTemp1 . '
	' . $__templater->button('Search logs', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/logs/orders/search', ), false),
		'icon' => 'search',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['entries'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['entries'])) {
			foreach ($__vars['entries'] AS $__vars['entry']) {
				$__compilerTemp2 .= '
						' . $__templater->callMacro('public:dbtech_ecommerce_order_list_macros', 'order', array(
					'order' => $__vars['entry'],
					'context' => 'admin',
					'linkPrefix' => 'dbtech-ecommerce/logs/orders',
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__compilerTemp2 . '
				', array(
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['entries'], $__vars['total'], ), true) . '</span>
			</div>
		</div>
		' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'dbtech-ecommerce/logs/orders',
			'params' => array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ),
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . ($__vars['criteria'] ? 'No records matched.' : 'No entries have been logged.') . '</div>
';
	}
	return $__finalCompiled;
}
);