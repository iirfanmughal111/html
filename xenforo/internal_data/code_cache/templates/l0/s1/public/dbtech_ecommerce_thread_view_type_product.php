<?php
// FROM HASH: 3db7179508eaa3ab0c950e6073255ece
return array(
'extends' => function($__templater, array $__vars) { return 'thread_view'; },
'extensions' => array('content_top' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	if ($__vars['product']) {
		$__finalCompiled .= '
		';
		$__vars['originalH1'] = $__templater->preEscaped($__templater->func('page_h1', array('')));
		$__finalCompiled .= '
		';
		$__vars['originalDescription'] = $__templater->preEscaped($__templater->func('page_description'));
		$__finalCompiled .= '

		';
		$__templater->pageParams['noH1'] = true;
		$__finalCompiled .= '
		';
		$__templater->pageParams['pageDescription'] = $__templater->preEscaped('');
		$__templater->pageParams['pageDescriptionMeta'] = true;
		$__finalCompiled .= '

		';
		$__templater->includeCss('dbtech_ecommerce.less');
		$__finalCompiled .= '

		' . $__templater->callMacro(null, 'dbtech_ecommerce_product_wrapper_macros::header', array(
			'product' => $__vars['product'],
			'titleHtml' => $__vars['originalH1'],
			'metaHtml' => $__vars['originalDescription'],
		), $__vars) . '

		<div class="block">
			<div class="block-container">
				' . $__templater->callMacro(null, 'dbtech_ecommerce_product_wrapper_macros::description', array(
			'product' => $__vars['product'],
		), $__vars) . '

				' . $__templater->callMacro(null, 'dbtech_ecommerce_product_wrapper_macros::tabs', array(
			'product' => $__vars['product'],
			'pageSelected' => 'discussion',
		), $__vars) . '
			</div>
		</div>

		' . $__templater->callMacro(null, 'dbtech_ecommerce_product_wrapper_macros::sidebar', array(
			'product' => $__vars['product'],
			'category' => $__vars['product']['Category'],
			'showCheckout' => $__vars['showCheckout'],
		), $__vars) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->renderExtension('content_top', $__vars, $__extensions);
	return $__finalCompiled;
}
);