<?php
// FROM HASH: d6071fa6ce7d18f3a4ebdf9291fb6e8c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Media category permissions');
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['categoryTree'], 'countChildren', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'media-categories',
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
						' . $__templater->dataRow(array(
					'rowclass' => ($__vars['customPermissions'][$__vars['category']['category_id']] ? 'dataList-row--custom' : ''),
				), array(array(
					'class' => 'dataList-cell--link dataList-cell--main',
					'hash' => $__vars['category']['category_id'],
					'_type' => 'cell',
					'html' => '
								<a href="' . $__templater->func('link', array('permissions/media-categories', $__vars['category'], ), true) . '">
									<div class="u-depth' . $__templater->escape($__vars['treeEntry']['depth']) . '">
										<div class="dataList-mainRow">' . $__templater->escape($__vars['category']['title']) . '</div>
									</div>
								</a>
							',
				),
				array(
					'href' => $__templater->func('link', array('media-gallery/categories/edit', $__vars['category'], ), false),
					'_type' => 'action',
					'html' => 'Edit',
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
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->func('count', array($__templater->method($__vars['categoryTree'], 'getFlattened', array(0, )), ), false), ), true) . '</span>
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