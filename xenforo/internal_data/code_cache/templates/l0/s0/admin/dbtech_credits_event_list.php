<?php
// FROM HASH: 26694cc7cd5edd32750184fdd2c24303
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Events');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add event', array(
		'href' => $__templater->func('link', array('dbtech-credits/events/add', ), false),
		'overlay' => 'true',
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['currencies'])) {
		foreach ($__vars['currencies'] AS $__vars['currencyId'] => $__vars['currencyName']) {
			$__compilerTemp1 .= '
				<a href="' . $__templater->func('link', array('dbtech-credits/events', null, array('currency_id' => $__vars['currencyId'], ), ), true) . '"
				   class="tabs-tab ' . (($__vars['currencyId'] == $__vars['currency']) ? 'is-active' : '') . '">' . $__templater->escape($__vars['currencyName']) . '</a>
			';
		}
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['events'])) {
		foreach ($__vars['events'] AS $__vars['event']) {
			$__compilerTemp2 .= '
					' . $__templater->dataRow(array(
			), array(array(
				'hash' => $__vars['event']['event_id'],
				'href' => $__templater->func('link', array('dbtech-credits/events/edit', $__vars['event'], ), false),
				'label' => $__templater->escape($__vars['event']['title']),
				'hint' => '
								' . $__templater->escape($__vars['event']['EventTriggerTitle']) . '
							',
				'explain' => '
								<ul class="listInline listInline--bullet">
									<li>' . $__templater->escape($__vars['event']['main_add']) . '</li>
									<li>' . ($__vars['event']['charge'] ? 'Charged' : 'Not charged') . '</li>
									<li>' . ($__vars['event']['moderate'] ? 'Moderated' : 'Not moderated') . '</li>
								</ul>
							',
				'_type' => 'main',
				'html' => '',
			),
			array(
				'name' => 'active[' . $__vars['event']['event_id'] . ']',
				'selected' => $__vars['event']['active'],
				'class' => 'dataList-cell--separated',
				'submit' => 'true',
				'tooltip' => 'Enable / disable \'' . $__vars['event']['title'] . '\'',
				'_type' => 'toggle',
				'html' => '',
			),
			array(
				'href' => $__templater->func('link', array('dbtech-credits/events/delete', $__vars['event'], ), false),
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
		'key' => 'dbtech-credits/events',
		'class' => 'block-outer-opposite',
	), $__vars) . '
	</div>
	<div class="block-container">
		<h2 class="block-tabHeader tabs hScroller" data-xf-init="h-scroller" role="tablist">
			<span class="hScroller-scroll">
			' . $__compilerTemp1 . '
			</span>
		</h2>
		<div class="block-body">
			' . $__templater->dataList('
				' . $__compilerTemp2 . '
			', array(
	)) . '
		</div>
		<div class="block-footer">
			<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['events'], ), true) . '</span>
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-credits/events/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);