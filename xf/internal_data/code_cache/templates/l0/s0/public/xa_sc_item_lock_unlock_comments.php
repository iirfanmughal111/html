<?php
// FROM HASH: 834ac800de4b78633256e691e4c7a89c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['item']['comments_open']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Lock comments');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Unlock comments');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['item']['comments_open']) {
		$__compilerTemp1 .= '
					' . 'Please confirm that you want to lock comments on' . $__vars['xf']['language']['label_separator'] . '
				';
	} else {
		$__compilerTemp1 .= '
					' . 'Please confirm that you want to unlock comments on' . $__vars['xf']['language']['label_separator'] . '
				';
	}
	$__compilerTemp2 = '';
	if ($__vars['item']['comments_open']) {
		$__compilerTemp2 .= '
			' . $__templater->formSubmitRow(array(
			'submit' => 'Lock comments',
		), array(
			'rowtype' => 'simple',
		)) . '
		';
	} else {
		$__compilerTemp2 .= '
			' . $__templater->formSubmitRow(array(
			'submit' => 'Unlock comments',
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
		'action' => $__templater->func('link', array('showcase/lock-unlock-comments', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);