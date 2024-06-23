<?php
// FROM HASH: 4df20a0d56947c4adaf44ed0560e8971
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Transaction #' . $__templater->escape($__vars['entry']['transaction_id']) . '');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			';
	$__vars['currency'] = $__vars['entry']['Currency'];
	$__finalCompiled .= '

			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('users/edit', $__vars['entry']['SourceUser'], ), true) . '">' . $__templater->escape($__vars['entry']['SourceUser']['username']) . '</a>
			', array(
		'label' => 'Source User',
	)) . '
			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('users/edit', $__vars['entry']['TargetUser'], ), true) . '">' . $__templater->escape($__vars['entry']['TargetUser']['username']) . '</a>
			', array(
		'label' => 'Target User',
	)) . '

			';
	$__compilerTemp1 = '';
	if ($__vars['entry']['transaction_state'] == 'visible') {
		$__compilerTemp1 .= '
						' . 'Visible' . '
					';
	} else if ($__vars['entry']['transaction_state'] == 'moderated') {
		$__compilerTemp1 .= '
						' . 'Awaiting approval' . '
					';
	} else if ($__vars['entry']['transaction_state'] == 'skipped') {
		$__compilerTemp1 .= '
						' . 'Skipped' . '
					';
	} else if ($__vars['entry']['transaction_state'] == 'skipped_maximum') {
		$__compilerTemp1 .= '
						' . 'Skipped (maximum applications)' . '
					';
	} else if ($__vars['entry']['transaction_state'] == 'deleted') {
		$__compilerTemp1 .= '
						' . 'Deleted' . '
					';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__templater->escape($__vars['currency']['prefix']) . $__templater->filter($__vars['entry']['amount'], array(array('number', array($__vars['currency']['decimals'], )),), true) . $__templater->escape($__vars['currency']['suffix']) . ' ' . $__templater->escape($__vars['currency']['title']) . '
				<div class="u-muted">
					' . $__compilerTemp1 . '
				</div>
			', array(
		'label' => 'Amount',
	)) . '

			' . $__templater->formRow('
				' . $__templater->func('date_dynamic', array($__vars['entry']['dateline'], array(
		'data-full-date' => 'true',
	))) . '
			', array(
		'label' => 'Date',
	)) . '
			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('dbtech-credits/events/edit', $__vars['entry']['Event'], ), true) . '">' . $__templater->escape($__vars['entry']['Event']['title']) . '</a>
			', array(
		'label' => 'Event',
	)) . '
			' . $__templater->formRow('
				' . $__templater->escape($__templater->method($__vars['eventTrigger'], 'getTitle', array())) . '
			', array(
		'label' => 'Event Trigger',
	)) . '

			';
	if ($__vars['entry']['message']) {
		$__finalCompiled .= '
				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . $__templater->escape($__vars['entry']['message']) . '
				', array(
			'label' => 'Optional message',
		)) . '
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);