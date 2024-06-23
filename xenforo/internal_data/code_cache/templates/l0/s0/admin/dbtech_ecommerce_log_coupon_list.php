<?php
// FROM HASH: 0b23c28f371c9fb16a72705d36c3b183
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Coupon log');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Search logs', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/logs/coupons/search', ), false),
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
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['entries'])) {
			foreach ($__vars['entries'] AS $__vars['entry']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
				), array(array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/logs/coupons', $__vars['entry'], ), false),
					'overlay' => 'true',
					'label' => '
									' . ($__templater->escape($__vars['entry']['Coupon']['title']) ?: 'Unknown coupon') . '
								',
					'hint' => '
									' . ($__vars['entry']['order_id'] ? 'Order #' . $__templater->escape($__vars['entry']['order_id']) . ' / Order item #' . $__templater->escape($__vars['entry']['order_item_id']) . '' : 'Unknown order') . '
								',
					'explain' => '
									<ul class="listInline listInline--bullet">
										<li>' . $__templater->func('date_dynamic', array($__vars['entry']['log_date'], array(
					'data-full-date' => 'true',
				))) . '</li>
										<li>' . ($__vars['entry']['User'] ? $__templater->escape($__vars['entry']['User']['username']) : 'Unknown user') . '</li>
										<li>' . ($__vars['entry']['Ip'] ? $__templater->filter($__vars['entry']['Ip']['ip'], array(array('ip', array()),), true) : 'Unknown IP address') . '</li>
									</ul>
								',
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
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['entries'], $__vars['total'], ), true) . '</span>
			</div>
		</div>

		' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'dbtech-ecommerce/logs/coupons',
			'params' => array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ),
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '

	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'There are no coupon usage logs to display.' . '</div>
';
	}
	return $__finalCompiled;
}
);