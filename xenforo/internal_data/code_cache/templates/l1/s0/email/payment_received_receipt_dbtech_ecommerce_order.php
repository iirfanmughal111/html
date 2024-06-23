<?php
// FROM HASH: 941f1fea91979eb65f4c322eeef6c5f8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>' . 'Receipt for your purchase at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '' . '</mail:subject>

<p>' . 'Thank you for purchasing from <a href="' . $__templater->func('link', array('canonical:index', ), true) . '">' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '</a>.' . '</p>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td><b>' . 'Purchased item' . '</b></td>
	<td align="right"><b>' . 'Price' . '</b></td>
</tr>
';
	if ($__templater->isTraversable($__vars['purchasable']['purchasable']['Items'])) {
		foreach ($__vars['purchasable']['purchasable']['Items'] AS $__vars['item']) {
			$__finalCompiled .= '
	<tr>
		<td><a href="' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['item']['Product'], ), true) . '">' . $__templater->escape($__vars['item']['quantity']) . 'x ' . $__templater->escape($__vars['item']['Product']['title']) . '</a></td>
		<td align="right">' . $__templater->filter($__vars['item']['price'], array(array('currency', array($__vars['purchasable']['purchasable']['currency'], )),), true) . '</td>
	</tr>
';
		}
	}
	$__finalCompiled .= '
</table>

';
	if ($__vars['hasInvoice']) {
		$__finalCompiled .= '
	<p>' . 'An invoice for your purchase has been attached to this email.' . '</p>
';
	}
	$__finalCompiled .= '

<p><a href="' . $__templater->func('link', array('canonical:dbtech-ecommerce/account', ), true) . '" class="button">' . 'View your orders' . '</a></p>

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