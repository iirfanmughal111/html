<?php
// FROM HASH: 4791c6040fbac296d1918ba0f618382d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Reviews');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'reviews';
	$__templater->wrapTemplate('dbtech_ecommerce_product_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="block-body">
	';
	if ($__templater->isTraversable($__vars['reviews'])) {
		foreach ($__vars['reviews'] AS $__vars['review']) {
			$__finalCompiled .= '
		' . $__templater->callMacro('dbtech_ecommerce_product_review_macros', 'review', array(
				'review' => $__vars['review'],
				'product' => $__vars['product'],
			), $__vars) . '
	';
		}
	}
	$__finalCompiled .= '
</div>

';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
			' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/reviews',
		'data' => $__vars['product'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
	<div class="block-outer block-outer--after">
		' . $__compilerTemp2 . '
	</div>
';
	}
	return $__finalCompiled;
}
);