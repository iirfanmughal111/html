<?php
// FROM HASH: 8ae01ddd20bfec1d1d3642febb32dbb5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['isWatched']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Unwatch media item');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Watch media item');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['mediaItem'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['isWatched']) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('
					' . 'Are you sure you want to unwatch this media item?' . '
				', array(
			'rowtype' => 'confirm',
		)) . '

				' . $__templater->formHiddenVal('stop', '1', array(
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->formRadioRow(array(
			'name' => 'notify_on',
			'value' => 'comment',
		), array(array(
			'value' => 'comment',
			'label' => 'New comments',
			'_type' => 'option',
		),
		array(
			'value' => '',
			'label' => 'Don\'t send notifications',
			'_type' => 'option',
		)), array(
			'label' => 'Send notifications for',
		)) . '

				' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'send_alert',
			'value' => '1',
			'selected' => true,
			'label' => 'Alerts',
			'_type' => 'option',
		),
		array(
			'name' => 'send_email',
			'value' => '1',
			'label' => 'Emails',
			'_type' => 'option',
		)), array(
			'label' => 'Send notifications via',
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['isWatched']) {
		$__compilerTemp2 .= '
			' . $__templater->formSubmitRow(array(
			'submit' => 'Unwatch',
			'icon' => 'notificationsOff',
		), array(
			'rowtype' => 'simple',
		)) . '
		';
	} else {
		$__compilerTemp2 .= '
			' . $__templater->formSubmitRow(array(
			'submit' => 'Watch',
			'icon' => 'notificationsOn',
		), array(
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '
		</div>
		' . $__compilerTemp2 . '
	</div>
', array(
		'action' => $__templater->func('link', array('media/watch', $__vars['mediaItem'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);