<?php
// FROM HASH: 4705e03029972f38d6586b16e4862f14
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['logo'] = 'data:image/png;base64,' . $__vars['logo'];
	$__compilerTemp2 = array(array(
		'_type' => 'cell',
		'html' => 'Product',
	)
,array(
		'_type' => 'cell',
		'html' => 'Price',
	));
	if ($__vars['hasDiscount']) {
		$__compilerTemp2[] = array(
			'_type' => 'cell',
			'html' => 'Discount',
		);
	}
	if ($__vars['hasSalesTax']) {
		$__compilerTemp2[] = array(
			'_type' => 'cell',
			'html' => 'Sales tax',
		);
	}
	$__compilerTemp2[] = array(
		'_type' => 'cell',
		'html' => 'Total',
	);
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['items'])) {
		foreach ($__vars['items'] AS $__vars['item']) {
			$__compilerTemp3 .= '
				';
			$__compilerTemp4 = '';
			if ($__vars['item']['item_type'] == 'new') {
				$__compilerTemp4 .= '
							' . $__vars['xf']['language']['parenthesis_open'] . 'New purchase' . $__vars['xf']['language']['parenthesis_close'] . '
						';
			} else if ($__vars['item']['item_type'] == 'upgrade') {
				$__compilerTemp4 .= '
							' . $__vars['xf']['language']['parenthesis_open'] . 'Upgrade' . $__vars['xf']['language']['parenthesis_close'] . '
						';
			} else if ($__vars['item']['item_type'] == 'renew') {
				$__compilerTemp4 .= '
							' . $__vars['xf']['language']['parenthesis_open'] . 'Renewal' . $__vars['xf']['language']['parenthesis_close'] . '
						';
			}
			$__compilerTemp5 = array(array(
				'_type' => 'cell',
				'html' => $__templater->escape($__vars['item']['quantity']) . 'x ' . $__templater->escape($__templater->method($__vars['item'], 'getFullTitle', array())) . '
						' . '

						' . $__compilerTemp4 . '
					',
			)
,array(
				'_type' => 'cell',
				'html' => $__templater->filter($__vars['item']['base_price'], array(array('currency', array($__vars['order']['currency'], )),), true),
			));
			if ($__vars['hasDiscount']) {
				$__compilerTemp5[] = array(
					'_type' => 'cell',
					'html' => $__templater->filter(($__vars['item']['coupon_discount'] + $__vars['item']['sale_discount']) * -1, array(array('currency', array($__vars['order']['currency'], )),), true),
				);
			}
			if ($__vars['hasSalesTax']) {
				$__compilerTemp5[] = array(
					'_type' => 'cell',
					'html' => $__templater->filter($__vars['item']['sales_tax'], array(array('currency', array($__vars['order']['currency'], )),), true),
				);
			}
			$__compilerTemp5[] = array(
				'_type' => 'cell',
				'html' => $__templater->filter($__vars['item']['price'], array(array('currency', array($__vars['order']['currency'], )),), true),
			);
			$__compilerTemp3 .= $__templater->dataRow(array(
				'rowclass' => 'dataList-row--noHover',
			), $__compilerTemp5) . '
			';
		}
	}
	$__compilerTemp6 = '';
	if ($__vars['isLastPage']) {
		$__compilerTemp6 .= '
				' . $__templater->dataRow(array(
			'rowtype' => 'subsection',
			'rowclass' => 'dataList-row--noHover',
		), array(array(
			'colspan' => $__vars['footerColspan'],
			'class' => 'footer-no-color',
			'_type' => 'cell',
			'html' => '&nbsp;',
		),
		array(
			'_type' => 'cell',
			'html' => 'Sub-total',
		),
		array(
			'_type' => 'cell',
			'html' => $__templater->filter($__vars['order']['sub_total'], array(array('currency', array($__vars['order']['currency'], )),), true),
		))) . '
				';
		if ($__vars['hasDiscount']) {
			$__compilerTemp6 .= '
					' . $__templater->dataRow(array(
				'rowtype' => 'subsection',
				'rowclass' => 'dataList-row--noHover',
			), array(array(
				'colspan' => $__vars['footerColspan'],
				'class' => 'footer-no-color',
				'_type' => 'cell',
				'html' => '&nbsp;',
			),
			array(
				'_type' => 'cell',
				'html' => 'Discounts',
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->filter($__vars['order']['discount_total'] * -1, array(array('currency', array($__vars['order']['currency'], )),), true),
			))) . '
				';
		}
		$__compilerTemp6 .= '
				';
		if ($__vars['hasShippingCost']) {
			$__compilerTemp6 .= '
					' . $__templater->dataRow(array(
				'rowtype' => 'subsection',
				'rowclass' => 'dataList-row--noHover',
			), array(array(
				'colspan' => $__vars['footerColspan'],
				'class' => 'footer-no-color',
				'_type' => 'cell',
				'html' => '&nbsp;',
			),
			array(
				'_type' => 'cell',
				'html' => 'Shipping cost',
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->filter($__vars['order']['shipping_cost'], array(array('currency', array($__vars['order']['currency'], )),), true),
			))) . '
				';
		}
		$__compilerTemp6 .= '
				';
		if ($__vars['hasSalesTax']) {
			$__compilerTemp6 .= '
					' . $__templater->dataRow(array(
				'rowtype' => 'subsection',
				'rowclass' => 'dataList-row--noHover',
			), array(array(
				'colspan' => $__vars['footerColspan'],
				'class' => 'footer-no-color',
				'_type' => 'cell',
				'html' => '&nbsp;',
			),
			array(
				'_type' => 'cell',
				'html' => 'Sales tax (' . $__templater->escape($__templater->method($__vars['order'], 'getSalesTaxRate', array('digital', ))) . '%)',
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->filter($__vars['order']['sales_tax'], array(array('currency', array($__vars['order']['currency'], )),), true),
			))) . '
				';
		}
		$__compilerTemp6 .= '
				' . $__templater->dataRow(array(
			'rowtype' => 'subsection',
			'rowclass' => 'dataList-row--noHover invoice-grand-total',
		), array(array(
			'colspan' => $__vars['footerColspan'],
			'class' => 'footer-no-color',
			'_type' => 'cell',
			'html' => '&nbsp;',
		),
		array(
			'_type' => 'cell',
			'html' => 'Total',
		),
		array(
			'_type' => 'cell',
			'html' => $__templater->filter($__vars['order']['order_total'], array(array('currency', array($__vars['order']['currency'], )),), true),
		))) . '
			';
	}
	$__compilerTemp7 = '';
	if ($__vars['isLastPage']) {
		$__compilerTemp7 .= '
			';
		$__compilerTemp8 = '';
		$__compilerTemp8 .= '
						' . '' . '
					';
		if (strlen(trim($__compilerTemp8)) > 0) {
			$__compilerTemp7 .= '
				<div class="pre-footer-closing-line">
					' . $__compilerTemp8 . '
				</div>
			';
		}
		$__compilerTemp7 .= '
		';
	}
	$__compilerTemp1['content'] = $__templater->preEscaped('
		' . $__templater->dataList('
			' . $__templater->dataRow(array(
		'rowtype' => 'header',
		'rowclass' => 'dataList-row--noHover',
	), $__compilerTemp2) . '

			' . $__compilerTemp3 . '

			' . $__compilerTemp6 . '
		', array(
	)) . '

		' . $__compilerTemp7 . '
	');
	$__finalCompiled .= $__templater->includeTemplate('dbtech_ecommerce_invoice_page_container', $__compilerTemp1);
	return $__finalCompiled;
}
);