<?php
// FROM HASH: 8e46553b920fb295c406cc74a364cadc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['numOrders'] > 0) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('
					<p class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
						<strong>' . 'Note' . $__vars['xf']['language']['label_separator'] . '</strong>
						' . 'Deleting this address will remove it from <b>' . $__templater->filter($__vars['numOrders'], array(array('number', array()),), true) . '</b> orders, which can make accounting for previous sales more difficult. This action cannot be undone. Please tick the box below to confirm you really wish to do this.' . '
					</p>
					<p>
						' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'reset_orders',
			'data-xf-init' => 'disabler',
			'data-container' => '.js-submitDisable',
			'label' => 'I have read the warning and I wish to reset <b>' . $__templater->filter($__vars['numOrders'], array(array('number', array()),), true) . '</b> order(s)',
			'_type' => 'option',
		))) . '
					</p>
				', array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to delete the following' . $__vars['xf']['language']['label_separator'] . '
				<strong><a href="' . $__templater->escape($__vars['contentUrl']) . '">' . $__templater->escape($__vars['contentTitle']) . '</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
		'rowclass' => 'js-submitDisable',
	)) . '
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__vars['confirmUrl'],
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);