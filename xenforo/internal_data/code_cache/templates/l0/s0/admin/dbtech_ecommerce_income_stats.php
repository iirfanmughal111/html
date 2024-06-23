<?php
// FROM HASH: e73ddb75789caad7c36f37b33957a680
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Income statistics');
	$__finalCompiled .= '

';
	$__templater->includeCss('public:chartist.css');
	$__finalCompiled .= '
';
	$__templater->includeCss('stats.less');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'prod' => 'xf/stats-compiled.js',
		'dev' => 'vendor/chartist/chartist.min.js, xf/stats.js',
	));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<h2 class="block-header">
			';
	if ($__vars['grouping'] == 'daily') {
		$__finalCompiled .= '
				' . 'Daily statistics' . '
			';
	} else if ($__vars['grouping'] == 'weekly') {
		$__finalCompiled .= '
				' . 'Weekly statistics' . '
			';
	} else if ($__vars['grouping'] == 'monthly') {
		$__finalCompiled .= '
				' . 'Monthly statistics' . '
			';
	}
	$__finalCompiled .= '
			<div class="block-desc">' . $__templater->func('date', array($__vars['start'], 'absolute', ), true) . ' - ' . $__templater->func('date', array($__vars['end'], 'absolute', ), true) . '</div>
		</h2>
		<div class="block-body">
			<div class="block-row">
				<div data-xf-init="stats">
					<script class="js-statsData" type="application/json">
						' . $__templater->filter($__vars['data'], array(array('json', array()),array('raw', array()),), true) . '
					</script>
					<script class="js-statsSeriesLabels" type="application/json">
						' . $__templater->filter($__vars['displayTypesPhrased'], array(array('json', array()),array('raw', array()),), true) . '
					</script>
					<div class="ct-chart ct-major-tenth js-statsChart"></div>
					<ul class="ct-legend js-statsLegend"></ul>
				</div>
			</div>
			';
	if ($__vars['grouping'] != 'daily') {
		$__finalCompiled .= '
				<div class="block-row block-row--minor">
					' . 'Values represent daily averages within each time period.' . '
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
		<h3 class="block-header">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up :next">
				' . 'Raw statistics' . '
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			';
	$__compilerTemp1 = array(array(
		'_type' => 'cell',
		'html' => 'Period',
	));
	if ($__templater->isTraversable($__vars['displayTypes'])) {
		foreach ($__vars['displayTypes'] AS $__vars['displayType']) {
			$__compilerTemp1[] = array(
				'_type' => 'cell',
				'html' => $__templater->escape($__vars['displayTypesPhrased'][$__vars['displayType']]),
			);
		}
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['data'])) {
		foreach ($__vars['data'] AS $__vars['stat']) {
			$__compilerTemp2 .= '
				';
			$__compilerTemp3 = array(array(
				'class' => 'dataList-cell--alt',
				'_type' => 'cell',
				'html' => $__templater->escape($__vars['stat']['label']),
			));
			if ($__templater->isTraversable($__vars['displayTypes'])) {
				foreach ($__vars['displayTypes'] AS $__vars['displayType']) {
					$__compilerTemp3[] = array(
						'_type' => 'cell',
						'html' => $__templater->filter($__vars['stat']['values'][$__vars['displayType']], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true),
					);
				}
			}
			$__compilerTemp2 .= $__templater->dataRow(array(
			), $__compilerTemp3) . '
			';
		}
	}
	$__finalCompiled .= $__templater->dataList('
				' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), $__compilerTemp1) . '
			' . $__compilerTemp2 . '
			', array(
		'class' => 'dataList--contained',
		'data-xf-init' => 'responsive-data-list',
	)) . '
		</div>
		<div class="block-footer">
			<span class="block-footer-counter">' . 'Total' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['total'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</span>
		</div>
	</div>
</div>

';
	$__compilerTemp4 = array(array(
		'value' => '-1',
		'label' => 'Date presets' . $__vars['xf']['language']['label_separator'],
		'_type' => 'option',
	));
	$__compilerTemp4[] = array(
		'_type' => 'optgroup',
		'options' => array(),
	);
	end($__compilerTemp4); $__compilerTemp5 = key($__compilerTemp4);
	$__compilerTemp4[$__compilerTemp5]['options'] = $__templater->mergeChoiceOptions($__compilerTemp4[$__compilerTemp5]['options'], $__vars['datePresets']);
	$__compilerTemp4[$__compilerTemp5]['options'][] = array(
		'value' => '1995-01-01',
		'label' => 'All time',
		'_type' => 'option',
	);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				<div class="inputGroup inputGroup--auto">
					' . $__templater->formDateInput(array(
		'name' => 'start',
		'value' => $__templater->func('date', array($__vars['start'], 'Y-m-d', ), false),
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'end',
		'value' => ($__vars['endDisplay'] ? $__templater->func('date', array($__vars['endDisplay'], 'Y-m-d', ), false) : ''),
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formSelect(array(
		'name' => 'date_preset',
		'class' => 'js-presetChange input--autoSize',
	), $__compilerTemp4) . '
				</div>
			', array(
		'label' => 'Date range',
		'rowtype' => 'input',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'grouping',
		'value' => $__vars['grouping'],
	), array(array(
		'value' => 'daily',
		'label' => 'Daily',
		'_type' => 'option',
	),
	array(
		'value' => 'weekly',
		'label' => 'Weekly',
		'_type' => 'option',
	),
	array(
		'value' => 'monthly',
		'label' => 'Monthly',
		'_type' => 'option',
	)), array(
		'label' => 'Grouping',
	)) . '

			' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_checkbox', array(
		'productId' => $__vars['displayTypes'],
		'inputName' => 'display_types',
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Show',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/income-stats', ), false),
		'class' => 'block',
	)) . '

';
	$__templater->inlineJs('
	$(function()
	{
		$(\'.js-presetChange\').change(function(e)
		{
			var $ctrl = $(this),
				value = $ctrl.val(),
				$form = $ctrl.closest(\'form\');

			if (value == -1)
			{
				return;
			}

			$form.find($ctrl.data(\'start\') || \'input[name=start]\').val(value);
			$form.find($ctrl.data(\'end\') || \'input[name=end]\').val(\'\');
			$form.submit();
		});
	});
');
	return $__finalCompiled;
}
);