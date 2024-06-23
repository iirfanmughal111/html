<?php
// FROM HASH: 4ff5f1d0bcfc9be405b23f0f240d740c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Publish item now');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to publish the following item now' . $__vars['xf']['language']['label_separator'] . '
				<strong><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->escape($__vars['item']['title']) . '</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Publish item now',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/publish-draft', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);