<?php
// FROM HASH: 2197ca6ff851f362441ccfa30750a169
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Delete product');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['product'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['hasChildren']) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('
					<p class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
						<strong>' . 'Note' . $__vars['xf']['language']['label_separator'] . '</strong>
						' . 'Deleting this item will also delete any and all of its child items. If you do not want this to happen, assign any child items a new parent item before continuing with this deletion.' . '
					</p>
				', array(
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['numDownloads'] > 0) {
		$__compilerTemp2 .= '
				' . $__templater->formInfoRow('
					<p class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
						<strong>' . 'Note' . $__vars['xf']['language']['label_separator'] . '</strong>
						' . 'Deleting this product will also delete the <b>' . $__templater->filter($__vars['numDownloads'], array(array('number', array()),), true) . '</b> download(s) belonging to this product.' . '
					</p>
				', array(
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__vars['numLicenses'] > 0) {
		$__compilerTemp3 .= '
				' . $__templater->formInfoRow('
					<p class="block-rowMessage block-rowMessage--error block-rowMessage--iconic">
						<strong>' . 'Warning' . $__vars['xf']['language']['label_separator'] . '</strong>
						' . 'Deleting this product will also delete <b>' . $__templater->filter($__vars['numLicenses'], array(array('number', array()),), true) . '</b> licenses belonging to this product. This action cannot be undone. Please tick the box below to confirm you really wish to do this.' . '
					</p>
					<p>
						' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'delete_licenses',
			'data-xf-init' => 'disabler',
			'data-container' => '.js-submitDisable',
			'label' => 'I have read the warning and I wish to delete <b>' . $__templater->filter($__vars['numLicenses'], array(array('number', array()),), true) . '</b> license(s)',
			'_type' => 'option',
		))) . '
					</p>
				', array(
		)) . '
			';
	}
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['product'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp4 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '
			
			' . $__compilerTemp2 . '

			' . $__compilerTemp3 . '

			' . $__templater->callMacro('dbtech_ecommerce_helper_action', 'delete_type', array(
		'content' => $__vars['product'],
		'stateKey' => 'product_state',
		'canHardDelete' => $__templater->method($__vars['product'], 'canDelete', array('hard', )),
	), $__vars) . '

			' . $__compilerTemp4 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
		'rowclass' => 'js-submitDisable',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/delete', $__vars['product'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);