<?php
// FROM HASH: a69b8a849b2f1a6c6bc83cb859cd15a8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['_noWrap']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Licenses');
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Licenses');
		$__finalCompiled .= '

	';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = 'user_licenses';
		$__templater->wrapTemplate('dbtech_ecommerce_product_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<span class="u-anchorTarget" id="user_licenses"></span>

';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '

';
	if ($__vars['_noWrap']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h2 class="block-formSectionHeader">' . 'Licenses' . '</h2>
';
	}
	$__finalCompiled .= '
<div class="block-body">
	<div class="structItemContainer">
		';
	if ($__templater->isTraversable($__vars['licenses'])) {
		foreach ($__vars['licenses'] AS $__vars['license']) {
			$__finalCompiled .= '
			' . $__templater->callMacro('dbtech_ecommerce_license_list_macros', 'license_list_entry', array(
				'license' => $__vars['license'],
				'children' => array(),
				'allowInlineMod' => false,
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '
	</div>
</div>
';
	if ($__vars['_noWrap']) {
		$__finalCompiled .= '
	</div>
</div>
';
	}
	return $__finalCompiled;
}
);