<?php
// FROM HASH: a3969cae3181f7d6ad5f0498216c3a8b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Distributor log');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Search logs', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/logs/distributors/search', ), false),
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
				if ($__vars['entry']['Product']) {
					$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/edit', $__vars['entry']['Product'], ), true) . '">
										' . $__templater->escape($__vars['entry']['Product']['title']) . '
									</a>
								';
				} else {
					$__compilerTemp3 .= '
									' . 'Unknown product' . '
								';
				}
				$__compilerTemp4 = '';
				if ($__vars['entry']['License']) {
					$__compilerTemp4 .= '
									' . $__templater->escape($__vars['entry']['License']['license_key']) . '<br />
									<div class="u-muted">
										';
					if ($__templater->method($__vars['entry']['License'], 'isLifetime', array())) {
						$__compilerTemp4 .= '
											' . 'dbtech_ecommerce_lifetime' . '
										';
					} else {
						$__compilerTemp4 .= '
											' . $__templater->func('date_dynamic', array($__vars['entry']['License']['expiry_date'], array(
							'data-full-date' => 'true',
						))) . '
										';
					}
					$__compilerTemp4 .= '
									</div>
								';
				} else {
					$__compilerTemp4 .= '
									' . 'Unknown license' . '
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
					'html' => $__templater->func('username_link', array($__vars['entry']['Distributor'], false, array(
					'href' => $__templater->func('link', array('users/edit', $__vars['entry']['Distributor'], ), false),
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
					'html' => '
								' . $__compilerTemp3 . '
							',
				),
				array(
					'_type' => 'cell',
					'html' => '
								' . $__compilerTemp4 . '
							',
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->func('username_link', array($__vars['entry']['Recipient'], false, array(
					'href' => $__templater->func('link', array('users/edit', $__vars['entry']['Recipient'], ), false),
				))),
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
			'html' => 'Product',
		),
		array(
			'_type' => 'cell',
			'html' => 'License',
		),
		array(
			'_type' => 'cell',
			'html' => 'Recipient',
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
			'link' => 'dbtech-ecommerce/logs/distributors',
			'params' => array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ),
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '

	</div>
	';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'There are no distributor log entries to display.' . '</div>
';
	}
	return $__finalCompiled;
}
);