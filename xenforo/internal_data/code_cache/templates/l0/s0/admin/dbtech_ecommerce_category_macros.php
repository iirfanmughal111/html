<?php
// FROM HASH: f36071d3f84042f687b060d329f148f0
return array(
'macros' => array('category_edit' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'categoryId' => '!',
		'row' => true,
		'includeAny' => false,
		'inputName' => 'category_id',
		'phrase' => 'Category',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->callMacro(null, 'category_select', array(
		'categoryId' => $__vars['categoryId'],
		'row' => $__vars['row'],
		'includeAny' => $__vars['includeAny'],
		'inputName' => $__vars['inputName'],
		'phrase' => $__vars['phrase'],
	), $__vars) . '
';
	return $__finalCompiled;
}
),
'category_change_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'categorys' => '!',
		'route' => '!',
		'routeData' => '!',
		'routeParams' => array(),
		'currentCategory' => null,
		'linkClass' => 'button button--link',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<a class="' . $__templater->escape($__vars['linkClass']) . ' menuTrigger"
		data-xf-click="menu"
		role="button"
		tabindex="0"
		aria-expanded="false"
		aria-haspopup="true">' . 'Category' . $__vars['xf']['language']['label_separator'] . ' ' . ($__vars['currentCategory'] ? $__templater->escape($__vars['currentCategory']['title']) : $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close']) . '</a>

	<div class="menu" data-menu="menu" aria-hidden="true">
		<div class="menu-content">
			<h3 class="menu-header">' . 'Categories' . '</h3>
			<a href="' . $__templater->func('link', array($__vars['route'], $__vars['routeData'], array('category_id' => null, ) + $__vars['routeParams'], ), true) . '"
				class="menu-linkRow ' . ((!$__vars['currentCategory']) ? 'is-selected' : '') . '">
				' . $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'] . '
			</a>
			';
	if ($__templater->isTraversable($__vars['categorys'])) {
		foreach ($__vars['categorys'] AS $__vars['category']) {
			$__finalCompiled .= '
				<a href="' . $__templater->func('link', array($__vars['route'], $__vars['routeData'], array('category_id' => $__vars['category']['category_id'], ) + $__vars['routeParams'], ), true) . '"
					class="menu-linkRow ' . (($__vars['currentCategory'] AND ($__vars['currentCategory']['category_id'] == $__vars['category']['category_id'])) ? 'is-selected' : '') . '">
					' . $__templater->escape($__vars['category']['title']) . '
				</a>
			';
		}
	}
	$__finalCompiled .= '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'category_select' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'categoryId' => '!',
		'row' => true,
		'class' => '',
		'includeAny' => false,
		'inputName' => 'category_id',
		'phrase' => 'Category',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['categoryRepo'] = $__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Category', ));
	$__finalCompiled .= '
	';
	$__compilerTemp1 = array(array(
		'value' => '',
		'_type' => 'option',
	));
	if ($__vars['includeAny']) {
		$__compilerTemp1[] = array(
			'value' => '_any',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		);
	}
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__templater->method($__vars['categoryRepo'], 'getCategoryOptionsData', array(false, )));
	$__vars['select'] = $__templater->preEscaped('
		' . $__templater->formSelect(array(
		'name' => $__vars['inputName'],
		'value' => $__vars['categoryId'],
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
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);