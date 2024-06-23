<?php
// FROM HASH: e4d0d850df8ebfd14bf55f1bd800c8a8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Publish item at a scheduled date/time');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['hours'])) {
		foreach ($__vars['hours'] AS $__vars['hour']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['hour'],
				'label' => $__templater->escape($__vars['hour']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['minutes'])) {
		foreach ($__vars['minutes'] AS $__vars['minute']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['minute'],
				'label' => $__templater->escape($__vars['minute']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = $__templater->mergeChoiceOptions(array(), $__vars['timeZones']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'item_publish_date',
		'value' => ($__vars['xf']['time'] ? $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false) : ''),
	)) . '
					<span class="inputGroup-text">
						' . 'Time' . $__vars['xf']['language']['label_separator'] . '
					</span>
					<span class="inputGroup" dir="ltr">
						' . $__templater->formSelect(array(
		'name' => 'item_publish_hour',
		'value' => '',
		'class' => 'input--inline input--autoSize',
	), $__compilerTemp1) . '
						<span class="inputGroup-text">:</span>
						' . $__templater->formSelect(array(
		'name' => 'item_publish_minute',
		'value' => '',
		'class' => 'input--inline input--autoSize',
	), $__compilerTemp2) . '
					</span>
				</div>
			', array(
		'label' => 'Publish date',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'item_timezone',
		'value' => $__vars['xf']['visitor']['timezone'],
	), $__compilerTemp3, array(
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/publish-draft-scheduled', $__vars['item'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);