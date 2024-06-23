<?php
// FROM HASH: 1426c68c1eab78852e2c24c387e99b2d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Categories');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
    <div class="buttonGroup">
        ' . $__templater->button('Add new category', array(
		'href' => $__templater->func('link', array('group-categories/add', ), false),
		'icon' => 'add',
		'overlay' => 'true',
	), '', array(
	)) . '
        ' . $__templater->button('Sort', array(
		'href' => $__templater->func('link', array('group-categories/sort', ), false),
		'icon' => 'sort',
		'overlay' => 'true',
	), '', array(
	)) . '
    </div>
');
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['nodeTree'], 'countChildren', array())) {
		$__finalCompiled .= '
    <div class="block">
        <div class="block-outer">
            ' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'nodes',
			'class' => 'block-outer-opposite',
		), $__vars) . '
        </div>
        <div class="block-container">
            <div class="block-body">
                ';
		$__compilerTemp1 = '';
		$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
		if ($__templater->isTraversable($__compilerTemp2)) {
			foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
				$__compilerTemp1 .= '
                        ';
				$__vars['node'] = $__vars['treeEntry']['record'];
				$__compilerTemp1 .= '
                        ' . $__templater->dataRow(array(
				), array(array(
					'class' => 'dataList-cell--link dataList-cell--main',
					'hash' => $__vars['node']['category_id'],
					'_type' => 'cell',
					'html' => '
                                <a href="' . $__templater->func('link', array('group-categories/edit', $__vars['node'], ), true) . '">
                                    <div class="u-depth' . $__templater->escape($__vars['treeEntry']['depth']) . '">
                                        <div class="dataList-mainRow">
                                            ' . $__templater->escape($__vars['node']['category_title']) . '
                                        </div>
                                    </div>
                                </a>
                            ',
				),
				array(
					'class' => 'dataList-cell--action u-hideMedium',
					'label' => 'Add' . $__vars['xf']['language']['ellipsis'],
					'_type' => 'popup',
					'html' => '

                                <div class="menu" data-menu="menu" aria-hidden="true">
                                    <div class="menu-content">
                                        <h3 class="menu-header">' . 'Add' . $__vars['xf']['language']['ellipsis'] . '</h3>
                                        <a href="' . $__templater->func('link', array('group-categories/add', null, array('parent_category_id' => $__vars['node']['parent_category_id'], ), ), true) . '"
                                           class="menu-linkRow" data-xf-click="overlay">' . 'Sibling' . '</a>
                                        <a href="' . $__templater->func('link', array('group-categories/add', null, array('parent_category_id' => $__vars['node']['category_id'], ), ), true) . '"
                                           class="menu-linkRow" data-xf-click="overlay">' . 'Child' . '</a>
                                    </div>
                                </div>
                            ',
				),
				array(
					'href' => $__templater->func('link', array('group-categories/delete', $__vars['node'], ), false),
					'_type' => 'delete',
					'html' => '',
				))) . '
                    ';
			}
		}
		$__finalCompiled .= $__templater->dataList('
                    ' . $__compilerTemp1 . '
                ', array(
		)) . '
            </div>
            <div class="block-footer">
                <span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->method($__vars['nodeTree'], 'getFlattened', array(0, )), ), true) . '</span>
            </div>
        </div>
    </div>
';
	} else {
		$__finalCompiled .= '
    <div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	return $__finalCompiled;
}
);