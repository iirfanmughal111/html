<?php
// FROM HASH: 19e065b2cd7e6aec6ba81705088e98f4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['title'])), $__vars['editLink'], array(
	));
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
	if ($__vars['includeAuthorAlert']) {
		$__compilerTemp4 .= '
				' . $__templater->callMacro('public:helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '

			' . $__compilerTemp2 . '

			' . $__compilerTemp3 . '

			' . $__templater->callMacro('public:dbtech_ecommerce_helper_action', 'delete_type', array(
		'content' => $__vars['entity'],
		'stateKey' => $__vars['stateKey'],
		'canHardDelete' => $__vars['canHardDelete'],
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

	' . $__templater->func('redirect_input', array(null, null, true)) . '

', array(
		'action' => $__vars['deleteLink'],
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);