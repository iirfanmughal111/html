<?php
// FROM HASH: 08e7535764fca6ebe3bae7b7c4e4cadd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Copyright info');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Copyright info');
	$__finalCompiled .= '

';
	$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['product']['tagline'], 250, array('stripBbCode' => true, ), ), false);
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['descSnippet'],
		'shareUrl' => $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['product'], ), false),
		'canonicalUrl' => $__templater->func('link', array('canonical:dbtech-ecommerce/copyright', $__vars['product'], ), false),
	), $__vars) . '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'copyright';
	$__templater->wrapTemplate('dbtech_ecommerce_product_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="block-body">
	<div class="block-row">
		' . $__templater->func('bb_code', array($__vars['product']['copyright_info'], 'dbtech_ecommerce_product_copyright', $__vars['product'], ), true) . '
	</div>
</div>';
	return $__finalCompiled;
}
);