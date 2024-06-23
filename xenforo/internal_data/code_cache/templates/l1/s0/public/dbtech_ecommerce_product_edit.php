<?php
// FROM HASH: 1c1fb162db4c57716a2e7300f0ce4062
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['product'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add product');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit product' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['product']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['product'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['product'], 'isUpdate', array()) AND $__templater->method($__vars['product'], 'canDelete', array('soft', ))) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/delete', $__vars['product'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->callMacro(null, 'dbtech_ecommerce_product_edit_macros::edit_form', array(
		'context' => 'public',
		'linkPrefix' => 'dbtech-ecommerce',
		'product' => $__vars['product'],
		'category' => $__vars['category'],
		'forumOptions' => $__vars['forumOptions'],
		'attachmentData' => $__vars['attachmentData'],
		'userGroups' => $__vars['userGroups'],
		'shippingZones' => $__vars['shippingZones'],
		'productOwner' => $__vars['productOwner'],
		'prefixes' => $__vars['prefixes'],
		'availableFields' => $__vars['availableFields'],
		'editableTags' => $__vars['editableTags'],
		'uneditableTags' => $__vars['uneditableTags'],
		'threadPrefixes' => $__vars['threadPrefixes'],
	), $__vars);
	return $__finalCompiled;
}
);