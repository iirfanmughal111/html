<?php
// FROM HASH: a9bbed928f05e28bdb46459069ed0219
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>' . 'New sale at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' (Order #' . $__templater->escape($__vars['item']['order_id']) . ')' . '</mail:subject>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td><b>' . 'Sold item' . '</b></td>
	<td align="right"><b>' . 'Shipping method' . '</b></td>
</tr>
<tr>
	<td><a href="' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['item']['Product'], ), true) . '">' . $__templater->escape($__vars['item']['quantity']) . 'x ' . $__templater->escape($__vars['item']['full_title']) . '</a></td>
	<td align="right">' . $__templater->escape($__vars['item']['ShippingMethod']['title']) . '</td>
</tr>
</table>

';
	$__vars['address'] = ($__vars['purchasable']['purchasable']['shipping_address_id'] ? $__vars['purchasable']['purchasable']['ShippingAddress'] : $__vars['purchasable']['purchasable']['Address']);
	$__finalCompiled .= '

<p><b>' . 'Shipping address' . '</b></p>

<p>
	' . $__templater->escape($__vars['address']['business_title']) . '<br />

	';
	if ($__vars['address']['business_co']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['address']['business_co']) . '<br />
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['address1']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['address']['address1']) . '<br />
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['address2']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['address']['address2']) . '<br />
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['address3']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['address']['address3']) . '<br />
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['address4']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['address']['address4']) . '<br />
	';
	}
	$__finalCompiled .= '

	' . $__templater->escape($__vars['address']['Country']['name']) . '
</p>

<p>' . 'Please ship this item as soon as possible. You may also wish to reply to this email to let the user know the item has shipped, along with the tracking number (if applicable).' . '</p>

';
	if ($__vars['item']['discussion_thread_id'] AND !$__templater->test($__vars['item']['Discussion'], 'empty', array())) {
		$__finalCompiled .= '
	' . '<p>' . $__templater->escape($__vars['purchaser']['username']) . ' provided additional information with this order. You can find this information here: ' . ((((('<a href="' . $__templater->func('link', array('canonical:threads', $__vars['item']['Discussion'], ), true)) . '">') . $__templater->func('prefix', array('thread', $__vars['item']['Discussion'], 'escaped', ), true)) . $__templater->escape($__vars['item']['Discussion']['title'])) . '</a>') . '</p>' . '
';
	}
	return $__finalCompiled;
}
);