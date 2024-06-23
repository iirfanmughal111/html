<?php
// FROM HASH: 418ff88edfa64996d280f37c23cb0afa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Store credit log');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add store credit', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/store-credit/add', ), false),
		'class' => 'button--cta',
		'icon' => 'add',
		'overlay' => 'true',
	), '', array(
	)) . '
	' . $__templater->button('Search logs', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/logs/store-credit/search', ), false),
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
						';
				$__compilerTemp2 = '';
				if ($__vars['entry']['Ip']) {
					$__compilerTemp2 .= '
									<a href="' . $__templater->func('link_type', array('public', 'misc/ip-info', null, array('ip' => $__templater->filter($__vars['entry']['Ip']['ip'], array(array('ip', array()),), false), ), ), true) . '" target="_blank" class="u-ltr">' . $__templater->filter($__vars['entry']['Ip']['ip'], array(array('ip', array()),), true) . '</a>
								';
				}
				$__compilerTemp3 = '';
				if ($__vars['entry']['Order']) {
					$__compilerTemp3 .= '
									' . 'Purchase of <a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/orders', $__vars['entry']['Order'], ), true) . '">Order #' . $__templater->escape($__vars['entry']['Order']['order_id']) . '</a>' . '
								';
				} else {
					$__compilerTemp3 .= '
									' . $__templater->escape($__templater->method($__vars['entry'], 'getReasonPhrase', array())) . '
								';
				}
				$__compilerTemp1 .= $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => $__templater->func('date_dynamic', array($__vars['entry']['log_date'], array(
					'data-full-date' => 'true',
				))),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->func('username_link', array($__vars['entry']['User'], false, array(
					'href' => $__templater->func('link', array('users/edit', $__vars['entry']['User'], ), false),
				))),
				),
				array(
					'class' => 'u-ltr',
					'_type' => 'cell',
					'html' => '
								' . $__compilerTemp2 . '
							',
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->filter($__vars['entry']['store_credit_amount'], array(array('number', array()),), true),
				),
				array(
					'class' => 'u-ltr',
					'_type' => 'cell',
					'html' => '
								' . $__compilerTemp3 . '
							',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'_type' => 'cell',
			'html' => 'Date / time',
		),
		array(
			'_type' => 'cell',
			'html' => 'User',
		),
		array(
			'_type' => 'cell',
			'html' => 'IP address',
		),
		array(
			'_type' => 'cell',
			'html' => 'Store credit amount',
		),
		array(
			'_type' => 'cell',
			'html' => 'Reason',
		))) . '

					' . $__compilerTemp1 . '
				', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['entries'], $__vars['total'], ), true) . '</span>
			</div>
		</div>

		' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'dbtech-ecommerce/logs/store-credit',
			'params' => array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ),
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '

	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'There are no store credit logs to display.' . '</div>
';
	}
	return $__finalCompiled;
}
);