<?php
// FROM HASH: 25379cc9f372e42384261354c130e30d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm payment details');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->filter($__vars['purchase']['cost'], array(array('currency', array($__vars['purchase']['currency'], )),), true) . '
			', array(
		'label' => 'Original price',
	)) . '
			' . $__templater->formRow('
				' . $__templater->escape($__vars['currency']['prefix']) . $__templater->filter($__vars['cost'], array(array('number', array($__vars['currency']['decimals'], )),), true) . $__templater->escape($__vars['currency']['suffix']) . ' ' . $__templater->escape($__vars['currency']['title']) . '
			', array(
		'label' => 'Credits charged',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'purchase',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('purchase/process', null, array('request_key' => $__vars['purchaseRequest']['request_key'], ), ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);