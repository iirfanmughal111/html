<?php
// FROM HASH: ec44b8100589828a3696d76e898163af
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add download to' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['handlers']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->escape($__vars['product']['title']) . '
			', array(
		'label' => 'Product',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'download_type',
	), $__compilerTemp1, array(
		'label' => 'Download type',
	)) . '
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'add',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/release/add', $__vars['product'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);