<?php
// FROM HASH: b3dc9ecdd8e404161f834346d0ada8b6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Watched series');
	$__finalCompiled .= '

';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), ));
	$__finalCompiled .= '

';
	$__templater->includeCss('node_list.less');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['watchedSeries'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['series'])) {
			foreach ($__vars['series'] AS $__vars['id'] => $__vars['seriesItem']) {
				$__compilerTemp1 .= '

					';
				$__vars['seriesWatch'] = $__vars['watchedSeries'][$__vars['seriesItem']['series_id']];
				$__compilerTemp1 .= '

					';
				$__compilerTemp2 = '';
				if ($__vars['seriesWatch']['notify_on'] == 'series_part') {
					$__compilerTemp2 .= '<li>' . 'New items' . '</li>';
				}
				$__compilerTemp3 = '';
				if ($__vars['seriesWatch']['send_email']) {
					$__compilerTemp3 .= '<li>' . 'Emails' . '</li>';
				}
				$__compilerTemp4 = '';
				if ($__vars['seriesWatch']['send_alert']) {
					$__compilerTemp4 .= '<li>' . 'Alerts' . '</li>';
				}
				$__vars['bonusInfo'] = $__templater->preEscaped('
						<ul class="listInline listInline--bullet">
							' . $__compilerTemp2 . '
							' . $__compilerTemp3 . '
							' . $__compilerTemp4 . '
						</ul>
					');
				$__compilerTemp1 .= '
					' . $__templater->callMacro('xa_sc_series_list_macros', 'series_watch_item', array(
					'series' => $__vars['seriesItem'],
					'chooseName' => 'ids',
					'bonusInfo' => $__vars['bonusInfo'],
				), $__vars) . '

				';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-outer">' . $__templater->func('trim', array('
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'watched/showcase-series',
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
		'), false) . '</div>

		<div class="block-container">
			<div class="block-body">
				' . $__compilerTemp1 . '
			</div>
			<div class="block-footer block-footer--split">
				<span class="block-footer-counter"></span>
				<span class="block-footer-select">' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '< .block-container',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '</span>
				<span class="block-footer-controls">
					' . $__templater->formSelect(array(
			'name' => 'watch_action',
			'class' => 'input--inline',
		), array(array(
			'label' => 'With selected' . $__vars['xf']['language']['ellipsis'],
			'_type' => 'option',
		),
		array(
			'value' => 'send_email:on',
			'label' => 'Enable email notification',
			'_type' => 'option',
		),
		array(
			'value' => 'send_email:off',
			'label' => 'Disable email notification',
			'_type' => 'option',
		),
		array(
			'value' => 'send_alert:on',
			'label' => 'Enable alerts',
			'_type' => 'option',
		),
		array(
			'value' => 'send_alert:off',
			'label' => 'Disable alerts',
			'_type' => 'option',
		),
		array(
			'value' => 'delete',
			'label' => 'Stop watching',
			'_type' => 'option',
		))) . '
					' . $__templater->button('Go', array(
			'type' => 'submit',
		), '', array(
		)) . '
				</span>
			</div>
		</div>

		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'link' => 'watched/showcase-series',
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'perPage' => $__vars['perPage'],
		))) . '
		</div>
	', array(
			'action' => $__templater->func('link', array('watched/showcase-series/update', ), false),
			'ajax' => 'true',
			'class' => 'block',
			'autocomplete' => 'off',
		)) . '
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'You are not watching any series. ' . '</div>
';
	}
	return $__finalCompiled;
}
);