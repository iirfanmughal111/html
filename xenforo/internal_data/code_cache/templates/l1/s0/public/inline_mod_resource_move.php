<?php
// FROM HASH: 80339a6949263ac0d497d60365e9a9c8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeJs(array(
		'src' => 'xf/prefix_menu.js',
		'min' => '1',
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Move resources');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	$__compilerTemp2 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['resource_category_id'],
				'label' => $__templater->func('repeat_raw', array('&nbsp; ', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['resources'])) {
		foreach ($__vars['resources'] AS $__vars['resource']) {
			$__compilerTemp3 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['resource']['resource_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body js-prefixListenContainer">
			' . $__templater->formInfoRow('Are you sure you want to move ' . $__templater->escape($__vars['total']) . ' resource(s)?', array(
		'rowtype' => 'confirm',
	)) . '
			' . $__templater->formSelectRow(array(
		'name' => 'target_category_id',
		'value' => $__vars['first']['resource_category_id'],
		'class' => 'js-categoryList',
	), $__compilerTemp1, array(
		'label' => 'Destination category',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'apply_prefix',
		'label' => 'Apply prefix to selected resources',
		'_dependent' => array('
						' . $__templater->callMacro('prefix_macros', 'select', array(
		'type' => 'resource',
		'prefixes' => $__vars['prefixes'],
		'href' => $__templater->func('link', array('resources/prefixes', ), false),
		'listenTo' => '< .js-prefixListenContainer | .js-categoryList',
	), $__vars) . '
					'),
		'_type' => 'option',
	)), array(
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

			' . $__templater->callMacro('helper_action', 'author_alert', array(
		'selected' => ($__vars['total'] == 1),
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'move',
	), array(
	)) . '
	</div>

	' . $__compilerTemp3 . '

	' . $__templater->formHiddenVal('type', 'resource', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'move', array(
	)) . '
	' . $__templater->formHiddenVal('confirmed', '1', array(
	)) . '

	' . $__templater->func('redirect_input', array($__vars['redirect'], null, true)) . '
', array(
		'action' => $__templater->func('link', array('inline-mod', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);