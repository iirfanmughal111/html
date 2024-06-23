<?php
// FROM HASH: 1e4110d539d859ea66815a02ba5498fe
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>' . 'Receipt for your currency purchase at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '' . '</mail:subject>

<p>' . 'Thank you for purchasing currency at <a href="' . $__templater->func('link', array('canonical:index', ), true) . '">' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '</a>.' . '</p>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td><b>' . 'Purchased item' . '</b></td>
	<td align="right"><b>' . 'Price' . '</b></td>
</tr>
<tr>
	<td>' . $__templater->escape($__vars['purchasable']['title']) . '</td>
	<td align="right">' . $__templater->escape($__templater->method($__vars['purchasable']['purchasable'], 'getCostPhrase', array())) . '</td>
</tr>
</table>

';
	if ($__templater->method($__vars['xf']['toUser'], 'canUseContactForm', array())) {
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