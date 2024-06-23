<?php
// FROM HASH: b2423c2733d3bf2d16b919347d28cdb4
return array(
'macros' => array('product_edit' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'productId' => '!',
		'row' => true,
		'class' => '',
		'productsByCategory' => null,
		'includeBlank' => true,
		'includeAny' => false,
		'includeNone' => false,
		'licensesOnly' => false,
		'downloadsOnly' => false,
		'physicalOnly' => false,
		'addonOnly' => false,
		'inputName' => 'product_id',
		'phrase' => 'Product',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	' . $__templater->callMacro(null, 'product_select', array(
		'productId' => $__vars['productId'],
		'row' => $__vars['row'],
		'class' => $__vars['class'],
		'productsByCategory' => $__vars['productsByCategory'],
		'includeBlank' => $__vars['includeBlank'],
		'includeAny' => $__vars['includeAny'],
		'includeNone' => $__vars['includeNone'],
		'licensesOnly' => $__vars['licensesOnly'],
		'downloadsOnly' => $__vars['downloadsOnly'],
		'physicalOnly' => $__vars['physicalOnly'],
		'addonOnly' => $__vars['addonOnly'],
		'inputName' => $__vars['inputName'],
		'phrase' => $__vars['phrase'],
	), $__vars) . '
';
	return $__finalCompiled;
}
),
'product_select' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'productId' => '!',
		'row' => true,
		'class' => '',
		'productsByCategory' => null,
		'includeBlank' => true,
		'includeAny' => false,
		'includeNone' => false,
		'licensesOnly' => false,
		'downloadsOnly' => false,
		'physicalOnly' => false,
		'addonOnly' => false,
		'inputName' => 'product_id',
		'phrase' => 'Product',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__templater->test($__vars['productsByCategory'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__vars['productsByCategory'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductsByCategory', array());
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp1 = array();
	if ($__vars['includeBlank']) {
		$__compilerTemp1[] = array(
			'value' => '',
			'_type' => 'option',
		);
	}
	if ($__vars['includeAny']) {
		$__compilerTemp1[] = array(
			'value' => '_any',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		);
	}
	if ($__vars['includeNone']) {
		$__compilerTemp1[] = array(
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		);
	}
	if ($__templater->isTraversable($__vars['productsByCategory']['products'])) {
		foreach ($__vars['productsByCategory']['products'] AS $__vars['categoryId'] => $__vars['products']) {
			$__compilerTemp1[] = array(
				'label' => $__vars['productsByCategory']['categories'][$__vars['categoryId']]['record']['title'],
				'_type' => 'optgroup',
				'options' => array(),
			);
			end($__compilerTemp1); $__compilerTemp2 = key($__compilerTemp1);
			if ($__templater->isTraversable($__vars['products'])) {
				foreach ($__vars['products'] AS $__vars['productTreeEntry']) {
					$__compilerTemp1[$__compilerTemp2]['options'][] = array(
						'value' => $__vars['productTreeEntry']['record']['product_id'],
						'disabled' => ((((($__vars['licensesOnly'] AND (!$__templater->method($__vars['productTreeEntry']['record'], 'hasLicenseFunctionality', array()))) OR ($__vars['downloadsOnly'] AND (!$__templater->method($__vars['productTreeEntry']['record'], 'hasDownloadFunctionality', array())))) OR ($__vars['addonOnly'] AND (!$__templater->method($__vars['productTreeEntry']['record'], 'hasAddonFunctionality', array())))) OR ($__vars['physicalOnly'] AND (!$__templater->method($__vars['productTreeEntry']['record'], 'hasShippingFunctionality', array())))) ? true : false),
						'label' => $__templater->func('repeat', array('--', $__vars['productTreeEntry']['depth'], ), true) . '
							' . $__templater->escape($__vars['productTreeEntry']['record']['full_title']) . '
						',
						'_type' => 'option',
					);
				}
			}
		}
	}
	$__vars['select'] = $__templater->preEscaped('
		' . $__templater->formSelect(array(
		'name' => $__vars['inputName'],
		'value' => $__vars['productId'],
		'class' => $__vars['class'],
	), $__compilerTemp1) . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		' . $__templater->formRow('

			' . $__templater->filter($__vars['select'], array(array('raw', array()),), true) . '
		', array(
			'rowtype' => 'input',
			'label' => $__templater->escape($__vars['phrase']),
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['select'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'product_checkbox' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'productId' => '!',
		'row' => true,
		'class' => '',
		'productsByCategory' => null,
		'licensesOnly' => false,
		'downloadsOnly' => false,
		'physicalOnly' => false,
		'inputName' => 'product_id',
		'phrase' => 'Product',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->test($__vars['productsByCategory'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__vars['productsByCategory'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductsByCategory', array());
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['productsByCategory']['products'])) {
		foreach ($__vars['productsByCategory']['products'] AS $__vars['categoryId'] => $__vars['products']) {
			$__compilerTemp1[] = array(
				'label' => $__vars['productsByCategory']['categories'][$__vars['categoryId']]['record']['title'],
				'_type' => 'optgroup',
				'options' => array(),
			);
			end($__compilerTemp1); $__compilerTemp2 = key($__compilerTemp1);
			if ($__templater->isTraversable($__vars['products'])) {
				foreach ($__vars['products'] AS $__vars['productTreeEntry']) {
					$__compilerTemp1[$__compilerTemp2]['options'][] = array(
						'value' => $__vars['productTreeEntry']['record']['product_id'],
						'disabled' => (((($__vars['licensesOnly'] AND (!$__templater->method($__vars['productTreeEntry']['record'], 'hasLicenseFunctionality', array()))) OR ($__vars['downloadsOnly'] AND (!$__templater->method($__vars['productTreeEntry']['record'], 'hasDownloadFunctionality', array())))) OR ($__vars['physicalOnly'] AND (!$__templater->method($__vars['productTreeEntry']['record'], 'hasShippingFunctionality', array())))) ? true : false),
						'label' => $__templater->func('repeat', array('--', $__vars['productTreeEntry']['depth'], ), true) . '
							' . $__templater->escape($__vars['productTreeEntry']['record']['full_title']) . '
						',
						'_type' => 'option',
					);
				}
			}
		}
	}
	$__vars['checkbox'] = $__templater->preEscaped('
		' . $__templater->formCheckBox(array(
		'name' => $__vars['inputName'],
		'value' => $__vars['productId'],
		'class' => $__vars['class'],
	), $__compilerTemp1) . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		' . $__templater->formRow('

			' . $__templater->filter($__vars['checkbox'], array(array('raw', array()),), true) . '
		', array(
			'rowtype' => 'input',
			'label' => $__templater->escape($__vars['phrase']),
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['checkbox'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'product_display' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
		'row' => true,
		'class' => '',
		'phrase' => 'Product',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formRow('
		' . $__templater->escape($__vars['product']['title']) . '
	', array(
		'label' => $__templater->escape($__vars['phrase']),
		'class' => $__vars['class'],
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);