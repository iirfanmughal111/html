<?php
// FROM HASH: acd9f6664286096e4faa58f059f08900
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['currency']['title']));
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		';
	if (!$__templater->test($__vars['currency']['description'], 'empty', array())) {
		$__finalCompiled .= '
			<div class="block-row">
				' . $__templater->func('bb_code', array($__vars['currency']['description'], 'dbtech_credits_currency', $__vars['currency']['description'], ), true) . '
			</div>
		';
	}
	$__finalCompiled .= '
		<h2 class="block-tabHeader tabs hScroller" data-xf-init="h-scroller tabs" role="tablist">
			<span class="hScroller-scroll">
				';
	if (!$__vars['tab']) {
		$__finalCompiled .= '<a class="tabs-tab is-active" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('richest', ), true) . '">' . 'Richest Users' . '</a>';
	}
	$__finalCompiled .= '
				';
	if ($__vars['eventTriggers']['donate']) {
		$__finalCompiled .= '<a class="tabs-tab ' . (($__vars['tab'] == 'donate') ? 'is-active' : '') . '" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('donate', ), true) . '">' . 'Donate' . '</a>';
	}
	$__finalCompiled .= '
				';
	if ($__vars['eventTriggers']['adjust']) {
		$__finalCompiled .= '<a class="tabs-tab ' . (($__vars['tab'] == 'adjust') ? 'is-active' : '') . '" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('adjust', ), true) . '">' . 'Adjust' . '</a>';
	}
	$__finalCompiled .= '
				';
	if ($__vars['eventTriggers']['purchase']) {
		$__finalCompiled .= '<a class="tabs-tab ' . (($__vars['tab'] == 'purchase') ? 'is-active' : '') . '" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('purchase', ), true) . '">' . 'Purchase' . '</a>';
	}
	$__finalCompiled .= '
				';
	if ($__vars['eventTriggers']['redeem']) {
		$__finalCompiled .= '<a class="tabs-tab ' . (($__vars['tab'] == 'redeem') ? 'is-active' : '') . '" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('redeem', ), true) . '">' . 'Redeem' . '</a>';
	}
	$__finalCompiled .= '
				';
	if ($__vars['eventTriggers']['transfer']) {
		$__finalCompiled .= '<a class="tabs-tab ' . (($__vars['tab'] == 'transfer') ? 'is-active' : '') . '" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('transfer', ), true) . '">' . 'Transfer' . '</a>';
	}
	$__finalCompiled .= '
			</span>
		</h2>

		<ul class="tabPanes">

			';
	if (!$__vars['tab']) {
		$__finalCompiled .= '
				<li class="is-active"
					role="tabpanel"
					id="' . $__templater->func('unique_id', array('richest', ), true) . '">

					<div class="block-body block-row">
						' . $__templater->callMacro(null, 'dbtech_credits_currency_macros::richest', array(
			'currency' => $__vars['currency'],
			'showAmounts' => $__vars['currency']['show_amounts'],
		), $__vars) . '
					</div>
				</li>
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['eventTriggers']['donate']) {
		$__finalCompiled .= '
				<li class="' . (($__vars['tab'] == 'donate') ? 'is-active' : '') . '"
					role="tabpanel"
					id="' . $__templater->func('unique_id', array('donate', ), true) . '">

					' . $__templater->form('
						' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'value' => ($__vars['user'] ? $__vars['user']['username'] : ''),
			'ac' => 'single',
			'placeholder' => 'Member to donate to...' . $__vars['xf']['language']['ellipsis'],
		), array(
			'label' => 'Username',
		)) . '

						' . $__templater->formNumberBoxRow(array(
			'name' => 'amount',
			'min' => '0',
			'step' => 'any',
			'placeholder' => 'Amount to donate...',
		), array(
			'label' => 'Amount',
			'explain' => 'Do not include thousands separator. Use <b>.</b> as a decimal separator.',
		)) . '

						' . $__templater->formTextAreaRow(array(
			'name' => 'message',
			'placeholder' => 'Optional message',
			'autosize' => 'true',
		), array(
			'label' => 'Message',
		)) . '

						' . $__templater->formHiddenVal('currency_id', $__vars['currency']['currency_id'], array(
		)) . '

						' . $__templater->formSubmitRow(array(
			'icon' => 'money',
			'submit' => 'Donate',
		), array(
		)) . '
					', array(
			'class' => 'block',
			'action' => $__templater->func('link', array('dbtech-credits/currency/donate', $__vars['currency'], ), false),
			'ajax' => 'true',
			'data-redirect' => 'off',
			'data-reset-complete' => 'true',
		)) . '
				</li>
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['eventTriggers']['adjust']) {
		$__finalCompiled .= '
				<li class="' . (($__vars['tab'] == 'adjust') ? 'is-active' : '') . '"
					role="tabpanel"
					id="' . $__templater->func('unique_id', array('adjust', ), true) . '">

					' . $__templater->form('
						' . $__templater->formTextBoxRow(array(
			'ac' => 'single',
			'name' => 'username',
			'placeholder' => 'User to adjust credits for...' . $__vars['xf']['language']['ellipsis'],
			'value' => ($__vars['user'] ? $__vars['user']['username'] : ''),
		), array(
			'label' => 'Username',
		)) . '

						' . $__templater->formNumberBoxRow(array(
			'name' => 'amount',
			'min' => '0',
			'step' => 'any',
			'placeholder' => 'Amount',
		), array(
			'label' => 'Amount',
			'explain' => 'Do not include thousands separator. Use <strong>.</strong> as a decimal separator.',
		)) . '

						' . $__templater->formRadioRow(array(
			'name' => 'negate',
		), array(array(
			'value' => '0',
			'label' => 'Give',
			'checked' => 'checked',
			'_type' => 'option',
		),
		array(
			'value' => '1',
			'label' => 'Take',
			'_type' => 'option',
		)), array(
			'label' => 'Action',
		)) . '

						' . $__templater->formTextAreaRow(array(
			'name' => 'message',
			'placeholder' => 'Optional message',
			'autosize' => 'true',
		), array(
			'label' => 'Message',
		)) . '

						' . $__templater->formSubmitRow(array(
			'icon' => 'money',
			'submit' => 'Adjust',
		), array(
		)) . '

						' . $__templater->formHiddenVal('currency_id', $__vars['currency']['currency_id'], array(
		)) . '
					', array(
			'class' => 'block',
			'action' => $__templater->func('link', array('dbtech-credits/currency/adjust', $__vars['currency'], ), false),
			'ajax' => 'true',
			'data-redirect' => 'off',
			'data-reset-complete' => 'true',
		)) . '
				</li>
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['eventTriggers']['purchase']) {
		$__finalCompiled .= '
				<li class="' . (($__vars['tab'] == 'purchase') ? 'is-active' : '') . '"
					role="tabpanel"
					id="' . $__templater->func('unique_id', array('purchase', ), true) . '">

					<ul class="block-body listPlain">
						';
		if ($__templater->isTraversable($__vars['events'])) {
			foreach ($__vars['events'] AS $__vars['event']) {
				if ($__vars['event']['event_trigger_id'] == 'purchase') {
					$__finalCompiled .= '
							' . $__templater->callMacro('dbtech_credits_currency_macros', 'purchase_option', array(
						'event' => $__vars['event'],
						'profiles' => $__vars['profiles'],
						'currency' => $__vars['currency'],
					), $__vars) . '
						';
				}
			}
		}
		$__finalCompiled .= '
					</ul>
				</li>
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['eventTriggers']['redeem']) {
		$__finalCompiled .= '
				<li class="' . (($__vars['tab'] == 'redeem') ? 'is-active' : '') . '"
					role="tabpanel"
					id="' . $__templater->func('unique_id', array('redeem', ), true) . '">

					' . $__templater->form('
						' . $__templater->formTextBoxRow(array(
			'name' => 'code',
			'placeholder' => 'Code',
		), array(
			'label' => 'Redemption Code',
		)) . '

						' . $__templater->formSubmitRow(array(
			'icon' => 'money',
			'submit' => 'Redeem',
		), array(
		)) . '

						' . $__templater->formHiddenVal('currency_id', $__vars['currency']['currency_id'], array(
		)) . '
					', array(
			'class' => 'block',
			'action' => $__templater->func('link', array('dbtech-credits/currency/redeem', $__vars['currency'], ), false),
			'ajax' => 'true',
			'data-redirect' => 'off',
			'data-reset-complete' => 'true',
		)) . '
				</li>
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['eventTriggers']['transfer'] AND !$__templater->test($__vars['transferCurrencies'], 'empty', array())) {
		$__finalCompiled .= '
				<li class="' . (($__vars['tab'] == 'transfer') ? 'is-active' : '') . '"
					role="tabpanel"
					id="' . $__templater->func('unique_id', array('transfer', ), true) . '"
				>
					';
		$__compilerTemp1 = array(array(
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['transferCurrencies'])) {
			foreach ($__vars['transferCurrencies'] AS $__vars['transferCurrency']) {
				$__compilerTemp1[] = array(
					'value' => $__vars['transferCurrency']['currency_id'],
					'label' => $__templater->escape($__vars['transferCurrency']['title']),
					'_type' => 'option',
				);
			}
		}
		$__finalCompiled .= $__templater->form('
						' . $__templater->formSelectRow(array(
			'name' => 'to_currency_id',
		), $__compilerTemp1, array(
			'label' => 'Currency to transfer to',
		)) . '

						' . $__templater->formNumberBoxRow(array(
			'name' => 'amount',
			'min' => '0',
			'step' => 'any',
			'placeholder' => 'Amount to transfer...',
		), array(
			'label' => 'Amount',
			'explain' => 'Do not include thousands separator. Use <strong>.</strong> as a decimal separator.',
		)) . '

						' . $__templater->formSubmitRow(array(
			'icon' => 'money',
			'submit' => 'Transfer',
		), array(
		)) . '

						' . $__templater->formHiddenVal('currency_id', $__vars['currency']['currency_id'], array(
		)) . '
					', array(
			'class' => 'block',
			'action' => $__templater->func('link', array('dbtech-credits/currency/transfer', $__vars['currency'], ), false),
			'ajax' => 'true',
			'data-redirect' => 'off',
			'data-reset-complete' => 'true',
		)) . '
				</li>
			';
	}
	$__finalCompiled .= '
		</ul>
	</div>
</div>';
	return $__finalCompiled;
}
);