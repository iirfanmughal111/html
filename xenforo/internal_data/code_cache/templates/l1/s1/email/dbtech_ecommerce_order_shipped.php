<?php
// FROM HASH: 2de3c835264a719e348e5220072e56fc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>' . 'Your order #' . $__templater->escape($__vars['order']['order_id']) . ' at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' has shipped!' . '</mail:subject>

<p>' . 'Thank you for purchasing from <a href="' . $__templater->func('link', array('canonical:index', ), true) . '">' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '</a>. Your order has now shipped.' . '</p>

';
	if ($__vars['note']) {
		$__finalCompiled .= '
	<p>' . 'Note' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['note']) . '</p>
';
	}
	$__finalCompiled .= '

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td><b>' . 'Purchased item' . '</b></td>
	<td align="right"><b>' . 'Shipping method' . '</b></td>
</tr>
';
	if ($__templater->isTraversable($__vars['order']['Items'])) {
		foreach ($__vars['order']['Items'] AS $__vars['item']) {
			if ($__templater->method($__vars['item']['Product'], 'hasShippingFunctionality', array())) {
				$__finalCompiled .= '
	<tr>
		<td><a href="' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['item']['Product'], ), true) . '">' . $__templater->escape($__vars['item']['quantity']) . 'x ' . $__templater->escape($__vars['item']['full_title']) . '</a></td>
		<td align="right">' . $__templater->escape($__vars['item']['ShippingMethod']['title']) . '</td>
	</tr>
';
			}
		}
	}
	$__finalCompiled .= '
</table>

';
	$__vars['address'] = ($__vars['order']['shipping_address_id'] ? $__vars['order']['ShippingAddress'] : $__vars['order']['Address']);
	$__finalCompiled .= '

<p><b>' . 'Shipping address' . '</b>
	<br />
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

';
	if ($__vars['xf']['toUser'] AND $__templater->method($__vars['xf']['toUser'], 'canUseContactForm', array())) {
		$__finalCompiled .= '
	<p>' . 'Thank you for your purchase. If you have any questions, please <a href="' . $__templater->func('link', array('canonical:misc/contact', ), true) . '">contact us</a>.' . '</p>
';
	} else {
		$__finalCompiled .= '
	<p>' . 'Thank you for your purchase.' . '</p>
';
	}
	return $__finalCompiled;
}
);