<?php
// FROM HASH: 076b2307c3cbd5ef9cb637575447ac48
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Currencies');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add currency', array(
		'href' => $__templater->func('link', array('dbtech-credits/currencies/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['currencies'])) {
		foreach ($__vars['currencies'] AS $__vars['currency']) {
			$__compilerTemp1 .= '
					' . $__templater->dataRow(array(
			), array(array(
				'hash' => $__vars['currency']['currency_id'],
				'href' => $__templater->func('link', array('dbtech-credits/currencies/edit', $__vars['currency'], ), false),
				'label' => $__templater->escape($__vars['currency']['title']),
				'hint' => '
								' . ($__vars['currency']['is_display_currency'] ? 'Display currency' : '') . '
							',
				'explain' => '
								<ul class="listInline listInline--bullet">
									<li>' . $__templater->escape($__vars['currency']['table']) . '</li>
									<li>' . $__templater->escape($__vars['currency']['column']) . '</li>
								</ul>
							',
				'_type' => 'main',
				'html' => '',
			),
			array(
				'class' => 'dataList-cell--action',
				'label' => 'View' . $__vars['xf']['language']['ellipsis'],
				'_type' => 'popup',
				'html' => '

							<div class="menu" data-menu="menu" aria-hidden="true">
								<div class="menu-content">
									<h3 class="menu-header">' . 'View' . $__vars['xf']['language']['ellipsis'] . '</h3>
									<a href="' . $__templater->func('link', array('dbtech-credits/events', null, array('criteria' => array('currency_id' => $__vars['currency']['currency_id'], ), ), ), true) . '" class="menu-linkRow">' . 'View events' . '</a>
								</div>
							</div>
						',
			),
			array(
				'name' => 'active[' . $__vars['currency']['currency_id'] . ']',
				'selected' => $__vars['currency']['active'],
				'class' => 'dataList-cell--separated',
				'submit' => 'true',
				'tooltip' => 'Enable / disable \'' . $__vars['currency']['title'] . '\'',
				'_type' => 'toggle',
				'html' => '',
			),
			array(
				'href' => $__templater->func('link', array('dbtech-credits/currencies/delete', $__vars['currency'], ), false),
				'tooltip' => 'Delete' . ' ',
				'_type' => 'delete',
				'html' => '',
			))) . '
				';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-outer">
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => 'dbtech-credits/currencies',
		'class' => 'block-outer-opposite',
	), $__vars) . '
	</div>
	<div class="block-container">
		<div class="block-body">
			' . $__templater->dataList('
				' . $__compilerTemp1 . '
			', array(
	)) . '
		</div>
		<div class="block-footer">
			<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['currencies'], ), true) . '</span>
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-credits/currencies/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);