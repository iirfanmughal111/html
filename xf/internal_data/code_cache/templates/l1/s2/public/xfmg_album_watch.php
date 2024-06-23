<?php
// FROM HASH: 925355205894f819845826597a6f442b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['isWatched']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Unwatch album');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Watch album');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['album'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['isWatched']) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('
					' . 'Are you sure you want to unwatch this album?' . '
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
			'value' => 'media_comment',
		), array(array(
			'value' => 'media_comment',
			'label' => 'New media items and comments',
			'_type' => 'option',
		),
		array(
			'value' => 'media',
			'label' => 'New media items only',
			'_type' => 'option',
		),
		array(
			'value' => 'comment',
			'label' => 'New comments only',
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
		'action' => $__templater->func('link', array('media/albums/watch', $__vars['album'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);