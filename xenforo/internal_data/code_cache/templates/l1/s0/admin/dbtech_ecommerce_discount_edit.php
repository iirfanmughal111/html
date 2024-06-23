<?php
// FROM HASH: eb679e7a5ef87f105b63b3710940a759
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['discount'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add discount');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit discount' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['discount']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['discount'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/discounts/delete', $__vars['discount'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => ($__templater->method($__vars['discount'], 'exists', array()) ? $__vars['discount']['MasterTitle']['phrase_text'] : ''),
	), array(
		'label' => 'Title',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('

				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'discount_threshold',
		'value' => ($__vars['discount']['discount_threshold'] ?: 50),
		'min' => '1',
		'step' => 'any',
	)) . '
					<span class="inputGroup-text">' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '</span>
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Discount threshold',
		'explain' => 'This is the minimum amount (in ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . ') a customer\'s cart needs to contain in order for this discount to apply.',
	)) . '

			' . $__templater->formRow('

				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'discount_percent',
		'value' => ($__vars['discount']['discount_percent'] ?: 15),
		'min' => '1',
		'max' => '100',
		'step' => 'any',
	)) . '
					<span class="inputGroup-text">%</span>
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Discount percent',
		'explain' => 'The percent the customer\'s cart will be discounted by.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/discounts/save', $__vars['discount'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);