<?php
// FROM HASH: b63012593a6c6255adfc8320b73be76d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Change review reply date');
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
		'name' => 'reply_date',
		'value' => ($__vars['replyDate'] ? $__templater->func('date', array($__vars['replyDate'], 'picker', ), false) : ''),
	)) . '
					<span class="inputGroup-text">
						' . 'Time' . $__vars['xf']['language']['label_separator'] . '
					</span>
					<span class="inputGroup" dir="ltr">
						' . $__templater->formSelect(array(
		'name' => 'reply_hour',
		'value' => $__vars['replyHour'],
		'class' => 'input--inline input--autoSize',
	), $__compilerTemp1) . '
						<span class="inputGroup-text">:</span>
						' . $__templater->formSelect(array(
		'name' => 'reply_minute',
		'value' => $__vars['replyMinute'],
		'class' => 'input--inline input--autoSize',
	), $__compilerTemp2) . '
					</span>
				</div>
			', array(
		'label' => 'Reply date',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'reply_timezone',
		'value' => $__vars['xf']['visitor']['timezone'],
	), $__compilerTemp3, array(
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/review-reply/change-date', $__vars['reply'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);