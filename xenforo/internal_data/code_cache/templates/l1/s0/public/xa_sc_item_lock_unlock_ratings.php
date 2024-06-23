<?php
// FROM HASH: 2b356b1ab75fbbb0c9ae898f47834c62
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['item']['ratings_open']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Lock ratings');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Unlock ratings');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['item']['ratings_open']) {
		$__compilerTemp1 .= '
					' . 'Please confirm that you want to lock ratings on' . $__vars['xf']['language']['label_separator'] . '
				';
	} else {
		$__compilerTemp1 .= '
					' . 'Please confirm that you want to unlock ratings on' . $__vars['xf']['language']['label_separator'] . '
				';
	}
	$__compilerTemp2 = '';
	if ($__vars['item']['ratings_open']) {
		$__compilerTemp2 .= '
			' . $__templater->formSubmitRow(array(
			'submit' => 'Lock ratings',
		), array(
			'rowtype' => 'simple',
		)) . '
		';
	} else {
		$__compilerTemp2 .= '
			' . $__templater->formSubmitRow(array(
			'submit' => 'Unlock ratings',
		), array(
			'rowtype' => 'simple',
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . $__compilerTemp1 . '

				<strong><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->escape($__vars['item']['title']) . '</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>

		' . $__compilerTemp2 . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/lock-unlock-ratings', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);