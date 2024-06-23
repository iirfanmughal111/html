<?php
// FROM HASH: b322e21a6d421bfd7c138adeec9cd923
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

<a href="" class="blockoPayBtn button button--icon " data-toggle="modal" data-uid=7d7e97f3cf4d403c>
	' . 'fs_bitcoin_btn_text' . '  
	<img class="paymnet_btc_img " src="' . $__templater->func('base_url', array('styles/FS/BitCoinPayment/btc.png', ), true) . '">
</a>

<a href="" class="blockoPayBtn  button button--icon " data-toggle="modal" data-uid=a4757403ff494844>
	' . 'fs_bitcoin_btn_text' . '  
	<img class="paymnet_btc_img " src="' . $__templater->func('base_url', array('styles/FS/BitCoinPayment/btc.png', ), true) . '">
</a>';
	return $__finalCompiled;
}
);