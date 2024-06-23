<?php
// FROM HASH: 834b5519afeb322b71663d3ba9e64f0b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Item queue');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/item-queue', null, array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
	), $__vars) . '

<div class="block">
	<div class="block-outer">';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '

				';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
			<div class="block-outer-opposite">
				<div class="buttonGroup">
				' . $__compilerTemp2 . '
				</div>
			</div>
		';
	}
	$__finalCompiled .= $__templater->func('trim', array('

		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/item-queue',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp1 . '

	'), false) . '</div>

	<div class="block-container">
		' . $__templater->callMacro('xa_sc_item_queue_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'showcase/item-queue',
		'creatorFilter' => $__vars['creatorFilter'],
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_item_queue_macros', 'item_queue_item', array(
					'item' => $__vars['item'],
					'category' => $__vars['item']['Category'],
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . 'There are no items matching your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'There are no pending items to view' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/item-queue',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);