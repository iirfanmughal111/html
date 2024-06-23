<?php
// FROM HASH: 97904beac0015163b21098230d83543c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['category']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Featured items' . ' - ' . ($__vars['category']['meta_title'] ? $__templater->escape($__vars['category']['meta_title']) : $__templater->escape($__vars['category']['title'])));
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageH1'] = $__templater->preEscaped('Featured items');
		$__finalCompiled .= '
	';
		$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array()));
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Featured items');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="sc_item" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	<div class="block-outer">';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['canInlineMod']) {
		$__compilerTemp2 .= '
							' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
						';
	}
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
		' . $__compilerTemp1 . '
	'), false) . '</div>

	<div class="block-container">
		<div class="block-body">
			';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
					'item' => $__vars['item'],
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No items have been featured yet.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
	<div class="block-outer block-outer--after">
		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);