<?php
// FROM HASH: 265b78b26e5980cabf2e35dca8838c64
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['prefixesGrouped'], 'empty', array())) {
		$__compilerTemp1 .= '
		<div class="menu-row menu-row--separated">
			' . 'Prefix' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				' . $__templater->callMacro('prefix_macros', 'select', array(
			'prefixes' => $__vars['prefixesGrouped'],
			'type' => 'dbtechEcommerceProduct',
			'selected' => ($__vars['filters']['prefix_id'] ?: 0),
			'name' => 'prefix_id',
			'noneLabel' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
		), $__vars) . '
			</div>
		</div>
	';
	}
	$__compilerTemp2 = '';
	if (!$__templater->test($__vars['platformFilter'], 'empty', array())) {
		$__compilerTemp2 .= '
		<div class="menu-row menu-row--separated">
			' . 'Platform' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				';
		$__compilerTemp3 = array(array(
			'value' => '',
			'label' => 'Any',
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['platformFilter'])) {
			foreach ($__vars['platformFilter'] AS $__vars['platformId'] => $__vars['platform']) {
				$__compilerTemp3[] = array(
					'value' => $__vars['platformId'],
					'label' => $__templater->escape($__vars['platform']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp2 .= $__templater->formSelect(array(
			'name' => 'platform',
			'value' => $__vars['filters']['platform'],
		), $__compilerTemp3) . '
			</div>
		</div>
	';
	}
	$__compilerTemp4 = '';
	$__compilerTemp5 = $__templater->method($__vars['xf']['app'], 'getCustomFieldsForEdit', array('dbtechEcommerceProducts', $__vars['set'], 'user', null, $__vars['onlyInclude'], ));
	if ($__templater->isTraversable($__compilerTemp5)) {
		foreach ($__compilerTemp5 AS $__vars['fieldId'] => $__vars['fieldDefinition']) {
			$__compilerTemp4 .= '
		<div class="menu-row menu-row--separated">
			' . $__templater->escape($__vars['fieldDefinition']['title']) . ':
			<div class="u-inputSpacer">
				' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit_' . $__vars['fieldDefinition']['field_type'], array(
				'set' => $__vars['set'],
				'definition' => $__vars['fieldDefinition'],
				'editMode' => 'user',
				'namePrefix' => 'product_fields',
			), $__vars) . '
			</div>
		</div>
	';
		}
	}
	$__compilerTemp6 = array(array(
		'value' => 'last_update',
		'label' => 'Last update',
		'_type' => 'option',
	)
,array(
		'value' => 'creation_date',
		'label' => 'Creation date',
		'_type' => 'option',
	));
	if ($__vars['xf']['options']['dbtechEcommerceEnableRate']) {
		$__compilerTemp6[] = array(
			'value' => 'rating_weighted',
			'label' => 'Rating',
			'_type' => 'option',
		);
	}
	$__compilerTemp6[] = array(
		'value' => 'download_count',
		'label' => 'Downloads',
		'_type' => 'option',
	);
	$__compilerTemp6[] = array(
		'value' => 'title',
		'label' => 'Title',
		'_type' => 'option',
	);
	$__compilerTemp6[] = array(
		'value' => 'random',
		'label' => 'Random',
		'_type' => 'option',
	);
	$__finalCompiled .= $__templater->form('
	<!--[eCommerce:above_type]-->
	<div class="menu-row menu-row--separated">
		' . 'Type' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'type',
		'value' => $__vars['filters']['type'],
	), array(array(
		'value' => '',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => 'free',
		'label' => 'Free',
		'_type' => 'option',
	),
	array(
		'value' => 'paid',
		'label' => 'Paid',
		'_type' => 'option',
	),
	array(
		'value' => 'on_sale',
		'label' => 'On sale',
		'_type' => 'option',
	))) . '
		</div>
	</div>

	<!--[eCommerce:above_prefixes]-->
	' . $__compilerTemp1 . '

	<!--[eCommerce:above_platform]-->
	' . $__compilerTemp2 . '

	<!--[eCommerce:above_product_owner]-->
	<div class="menu-row menu-row--separated">
		' . 'Product owner' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'owner',
		'value' => ($__vars['ownerFilter'] ? $__vars['ownerFilter']['username'] : ''),
		'ac' => 'single',
	)) . '
		</div>
	</div>

	<!--[eCommerce:above_custom_fields]-->
	' . $__compilerTemp4 . '

	<!--[eCommerce:above_sort_by]-->
	<div class="menu-row menu-row--separated">
		' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
		<div class="inputGroup u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'order',
		'value' => ($__vars['filters']['order'] ?: $__vars['xf']['options']['dbtechEcommerceListDefaultOrder']),
	), $__compilerTemp6) . '
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
		'action' => $__templater->func('link', array(($__vars['category'] ? 'dbtech-ecommerce/categories/filters' : 'dbtech-ecommerce/filters'), $__vars['category'], ), false),
	));
	return $__finalCompiled;
}
);