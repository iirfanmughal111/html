<?php
// FROM HASH: 0e5fa1961d41c8df3c10fad91576472f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->inlineCss('
	.paymnet_btc_img{
	margin-left:3px;
	display:inline-block;
	width:25px;
	height:25px;
	}
	

');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Upgrade Account');
	$__finalCompiled .= '
';
	$__templater->includeCss('notices.less');
	$__finalCompiled .= '
<div class="block-container">
	<div class="block-body block-row">
		<h3>' . 'Upgrade Account' . '</h3>
		' . '<ul>
<li>These credits has a one-month validity and will stay in your account until you use it or decide to gift it. </li>
<li>You can purchase these credits with Bitcoin by following the \'Buying Crypto Tutorials\' link. </li>
<li>For additional help, feel free to send a private message to admin. </li>
<li>In case of payment failures, provide us with the \'Transaction ID\' (TxID) or details such as the crypto amount, date, and address to which you sent the payment.</li>
</ul>' . '
		<div class="block-warning">
			<ul class="notices notices--block js-notices" data-xf-init="notices" data-type="block" data-scroll-interval="6">
				<li class="notice js-notice notice--primary notice--hasImage notice--hidewide is-vis-processed" data-notice-id="1" data-delay-duration="0" data-display-duration="0" data-auto-dismiss="" data-visibility="wide">
					<div class="notice-content">
						' . 'WARNING: We do NOT support BITCOIN CASH (BCH). Bitcoin (BTC) is NOT the same thing.

If you send Bitcoin Cash you will lose your money as we will not receive it.

All sales are final. We do not refund cryptocurrency.' . '
					</div>
				</li>
			</ul>
		</div>
		';
	if ($__vars['xf']['visitor']['account_type'] == 1) {
		$__finalCompiled .= '
		<a href="" class="blockoPayBtn button button--icon " data-toggle="modal" data-uid=7d7e97f3cf4d403c>
			' . 'Upgrade with ' . '  
			<img class="paymnet_btc_img " src="' . $__templater->func('base_url', array('styles/FS/BitCoinPayment/btc.png', ), true) . '">
		</a>
		';
	} else if ($__vars['xf']['visitor']['account_type'] == 2) {
		$__finalCompiled .= '
		<a href="" class="blockoPayBtn  button button--icon " data-toggle="modal" data-uid=a4757403ff494844>
			' . 'Upgrade with ' . '  
			<img class="paymnet_btc_img " src="' . $__templater->func('base_url', array('styles/FS/BitCoinPayment/btc.png', ), true) . '">
		</a>
		';
	}
	$__finalCompiled .= '

	</div>
</div>';
	return $__finalCompiled;
}
);