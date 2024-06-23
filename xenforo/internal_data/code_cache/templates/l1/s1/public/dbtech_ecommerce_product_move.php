<?php
// FROM HASH: 63b56a78a6bb9be14d28a0d12382df29
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Move product');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['product'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	$__compilerTemp2 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'label' => $__templater->func('repeat_raw', array('&nbsp; ', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['product'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp3 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(
			'selected' => true,
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body js-prefixListenContainer">
			' . $__templater->formPrefixInputRow($__vars['prefixes'], array(
		'type' => 'dbtechEcommerceProduct',
		'prefix-value' => $__vars['product']['prefix_id'],
		'textbox-value' => $__vars['product']['title'],
		'href' => $__templater->func('link', array('dbtech-ecommerce/prefixes', ), false),
		'listen-to' => '< .js-prefixListenContainer | .js-categoryList',
		'autofocus' => 'autofocus',
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'target_category_id',
		'value' => $__vars['product']['product_category_id'],
		'class' => 'js-categoryList',
	), $__compilerTemp1, array(
		'label' => 'Destination category',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'notify_watchers',
		'value' => '1',
		'selected' => true,
		'label' => 'Notify members watching the destination category',
		'_type' => 'option',
	)), array(
	)) . '

			' . $__compilerTemp3 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/move', $__vars['product'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);