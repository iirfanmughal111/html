<?php
// FROM HASH: 3402363fd821b8edf7496c8dacf1435e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure you wish to unlock this content for ' . $__templater->escape($__templater->method($__vars['currency'], 'getFormattedValue', array($__vars['charge']['cost'], ))) . ' ' . $__templater->escape($__vars['currency']['title']) . '?' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'purchase',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-credits/currency/buy-content', $__vars['currency'], array('content_type' => $__vars['charge']['content_type'], 'content_id' => $__vars['charge']['content_id'], 'content_hash' => $__vars['charge']['content_hash'], ), ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);