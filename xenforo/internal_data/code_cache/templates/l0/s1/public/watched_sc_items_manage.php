<?php
// FROM HASH: 16aeda53ab1b2818b594d43a16b98cae
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Manage watched items');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['state'] == 'delete') {
		$__compilerTemp1 .= '
					' . 'Are you sure you want to stop watching <b>all</b> items?' . '
				';
	} else {
		$__compilerTemp1 .= '
					' . 'Are you sure you want to update your email notification settings for <b>all</b> items?' . '
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-row">
			' . $__templater->formInfoRow('
				' . $__compilerTemp1 . '
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Confirm',
		'icon' => 'notificationsOff',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('watched/showcase-items/manage', null, array('state' => $__vars['state'], ), ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);