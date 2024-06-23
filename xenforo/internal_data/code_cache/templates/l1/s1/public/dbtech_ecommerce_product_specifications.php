<?php
// FROM HASH: 7e6315743908aa0f199710f9a3b77f9b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Product specifications');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Product specifications');
	$__finalCompiled .= '

';
	$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['product']['tagline'], 250, array('stripBbCode' => true, ), ), false);
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['descSnippet'],
		'shareUrl' => $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['product'], ), false),
		'canonicalUrl' => $__templater->func('link', array('canonical:dbtech-ecommerce/specifications', $__vars['product'], ), false),
	), $__vars) . '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'specifications';
	$__templater->wrapTemplate('dbtech_ecommerce_product_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="block-body">
	<div class="block-row">
		' . $__templater->func('bb_code', array($__vars['product']['product_specification'], 'dbtech_ecommerce_product_specifications', $__vars['product'], ), true) . '
	</div>
</div>';
	return $__finalCompiled;
}
);