<?php
// FROM HASH: 428a36270848263b975fd0ecb09b444c
return array(
'macros' => array('category_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'categoryTree' => '!',
		'filterKey' => '!',
		'linkPrefix' => '!',
		'idKey' => 'category_id',
		'parentIdKey' => 'parent_category_id',
	); },
'global' => true,
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__templater->method($__vars['categoryTree'], 'countChildren', array())) {
		$__finalCompiled .= '
		<div class="block">
			<div class="block-outer">
				' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => $__vars['filterKey'],
			'class' => 'block-outer-opposite',
		), $__vars) . '
			</div>
			<div class="block-container">
				<div class="block-body">
					';
		$__compilerTemp1 = '';
		$__compilerTemp2 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
		if ($__templater->isTraversable($__compilerTemp2)) {
			foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
				$__compilerTemp1 .= '
							';
				$__vars['category'] = $__vars['treeEntry']['record'];
				$__compilerTemp1 .= '
							';
				$__compilerTemp3 = array(array(
					'class' => 'dataList-cell--link dataList-cell--main',
					'hash' => $__vars['category'][$__vars['idKey']],
					'_type' => 'cell',
					'html' => '
									<a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/edit', $__vars['category'], ), true) . '">
										<div class="u-depth' . $__templater->escape($__vars['treeEntry']['depth']) . '">
											<div class="dataList-mainRow">' . $__templater->escape($__vars['category']['title']) . '</div>
										</div>
									</a>
								',
				));
				if ($__vars['permissionContentType']) {
					$__compilerTemp3[] = array(
						'class' => ($__vars['customPermissions'][$__vars['category'][$__vars['idKey']]] ? 'dataList-cell--highlighted' : ''),
						'href' => $__templater->func('link', array($__vars['linkPrefix'] . '/permissions', $__vars['category'], ), false),
						'_type' => 'action',
						'html' => '
										' . 'Permissions' . '
									',
					);
				}
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--action u-hideMedium',
					'label' => 'Add' . $__vars['xf']['language']['ellipsis'],
					'_type' => 'popup',
					'html' => '

									<div class="menu" data-menu="menu" aria-hidden="true">
										<div class="menu-content">
											<h3 class="menu-header">' . 'Add' . $__vars['xf']['language']['ellipsis'] . '</h3>
											<a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/add', null, array($__vars['parentIdKey'] => $__vars['category'][$__vars['parentIdKey']], ), ), true) . '" class="menu-linkRow">' . 'Sibling' . '</a>
											<a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/add', null, array($__vars['parentIdKey'] => $__vars['category'][$__vars['parentIdKey']], 'clone_category_id' => $__vars['category']['category_id'], ), ), true) . '" class="menu-linkRow">' . 'Sibling (clone settings)' . '</a>
											<a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/add', null, array($__vars['parentIdKey'] => $__vars['category'][$__vars['idKey']], ), ), true) . '" class="menu-linkRow">' . 'Child' . '</a>
											<a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/add', null, array($__vars['parentIdKey'] => $__vars['category'][$__vars['idKey']], 'clone_category_id' => $__vars['category']['category_id'], ), ), true) . '" class="menu-linkRow">' . 'Child (clone settings)' . '</a>
										</div>
									</div>
								',
				);
				$__compilerTemp3[] = array(
					'href' => $__templater->func('link', array($__vars['linkPrefix'] . '/delete', $__vars['category'], ), false),
					'_type' => 'delete',
					'html' => '',
				);
				$__compilerTemp1 .= $__templater->dataRow(array(
				), $__compilerTemp3) . '
						';
			}
		}
		$__finalCompiled .= $__templater->dataList('
						' . $__compilerTemp1 . '
					', array(
		)) . '
				</div>
			</div>
		</div>
	';
	} else {
		$__finalCompiled .= '
		<div class="blockMessage">' . 'No categories have been created yet.' . '</div>
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Item categories');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'page_action', array(
		'linkPrefix' => 'xa-sc/categories',
	), $__vars) . '

' . $__templater->callMacro(null, 'category_list', array(
		'categoryTree' => $__vars['categoryTree'],
		'filterKey' => 'xa-sc-categories',
		'linkPrefix' => 'xa-sc/categories',
		'idKey' => 'category_id',
	), $__vars) . '

';
	return $__finalCompiled;
}
);