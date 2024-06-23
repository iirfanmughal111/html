<?php
// FROM HASH: 04fdfbb7ab829b2d6975d1a92e264dc3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Order fields');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped('These fields can be associated with specific products and will appear on the checkout screen.');
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

' . $__templater->includeTemplate('base_custom_field_list', $__vars);
	return $__finalCompiled;
}
);