<?php
// FROM HASH: 459ce85f9cd09e90284bd6e4f8044e9f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Remove item from series');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['series'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to remove this item from this series? ' . '
				<strong><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->escape($__vars['item']['title']) . '</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Remove item from series',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('showcase/series-part/remove', $__vars['seriesPart'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);