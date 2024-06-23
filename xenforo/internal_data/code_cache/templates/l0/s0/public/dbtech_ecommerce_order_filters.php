<?php
// FROM HASH: 86da712e110f53ff6a2f07008ef08589
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['addressFilter'], 'empty', array())) {
		$__compilerTemp1 .= '
		<div class="menu-row menu-row--separated">
			' . 'Address' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				';
		$__compilerTemp2 = array(array(
			'value' => '',
			'label' => 'Any',
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['addressFilter'])) {
			foreach ($__vars['addressFilter'] AS $__vars['addressId'] => $__vars['address']) {
				$__compilerTemp2[] = array(
					'value' => $__vars['addressId'],
					'label' => $__templater->escape($__vars['address']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp1 .= $__templater->formSelect(array(
			'name' => 'address',
			'value' => $__vars['filters']['address'],
		), $__compilerTemp2) . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= $__templater->form('
	<!--[eCommerce:above_address]-->
	' . $__compilerTemp1 . '

	<!--[eCommerce:above_state]-->
	<div class="menu-row menu-row--separated">
		' . 'Order state' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'state',
		'value' => $__vars['filters']['state'],
	), array(array(
		'value' => '',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => 'awaiting_payment',
		'label' => 'Awaiting payment',
		'_type' => 'option',
	),
	array(
		'value' => 'reversed',
		'label' => 'Reversed / refunded',
		'_type' => 'option',
	),
	array(
		'value' => 'completed',
		'label' => 'Completed',
		'_type' => 'option',
	))) . '
		</div>
	</div>

	<!--[eCommerce:above_sort_by]-->
	<div class="menu-row menu-row--separated">
		' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
		<div class="inputGroup u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'order',
		'value' => ($__vars['filters']['order'] ?: $__vars['xf']['options']['dbtechEcommerceListDefaultOrder']),
	), array(array(
		'value' => 'order_date',
		'label' => 'Order date',
		'_type' => 'option',
	),
	array(
		'value' => 'order_state',
		'label' => 'Order state',
		'_type' => 'option',
	),
	array(
		'value' => 'cost_amount',
		'label' => 'Order total',
		'_type' => 'option',
	))) . '
			<span class="inputGroup-splitter"></span>
			' . $__templater->formSelect(array(
		'name' => 'direction',
		'value' => ($__vars['filters']['direction'] ?: 'desc'),
	), array(array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	),
	array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	))) . '
		</div>
	</div>

	<div class="menu-footer">
		<span class="menu-footer-controls">
			' . $__templater->button('Filter', array(
		'type' => 'submit',
		'class' => 'button--primary',
	), '', array(
	)) . '
		</span>
	</div>
	' . $__templater->formHiddenVal('apply', '1', array(
	)) . '
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/account/filters', ), false),
	));
	return $__finalCompiled;
}
);