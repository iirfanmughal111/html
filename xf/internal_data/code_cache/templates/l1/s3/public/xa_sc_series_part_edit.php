<?php
// FROM HASH: b748d88632603d04a769d2564b2fa4d2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit series part');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['series'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formNumberBoxRow(array(
		'name' => 'display_order',
		'value' => $__vars['seriesPart']['display_order'],
		'min' => '0',
		'pattern' => '\\d*',
	), array(
		'label' => 'Display order',
		'explain' => 'The display order determines the order in which the items will appear on the series page as well as the order in which the item titles will appear within the series table of contents on item pages. 
<br><br>
<b>Tip:</b> Using increments of 10 or 100 when setting the display order allow you add a new item between existing items without having to adjust display orders of multiple existing items.',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/series-part/edit', $__vars['seriesPart'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);