<?php
// FROM HASH: 9293476971fb4a2f066a0aa5d152a225
return array(
'macros' => array('go_product_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
		'watchType' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar">
	<tr>
		<td>
			<a href="' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['product'], ), true) . '" class="button">' . 'View this product' . '</a>
		</td>
		<td align="' . ($__vars['xf']['isRtl'] ? 'left' : 'right') . '">
			';
	if ($__vars['watchType'] == 'product') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/ecommerce-products', ), true) . '" class="buttonFake">' . 'Watched products' . '</a>
			';
	} else if ($__vars['watchType'] == 'category') {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('canonical:watched/ecommerce-categories', ), true) . '" class="buttonFake">' . 'Watched categories' . '</a>
			';
	}
	$__finalCompiled .= '
		</td>
	</tr>
	</table>
';
	return $__finalCompiled;
}
),
'watched_category_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
		'category' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message. You must visit the forum to reply.</p>

<p class="minorText">This message was sent to you because you opted to watch the category "' . $__templater->escape($__vars['category']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of new products or updates.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'dbtech_ecommerce_category', 'id' => $__vars['category']['category_id'], ), ), true) . '">disable emails from this category</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
),
'watched_product_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '<p class="minorText">Please do not reply to this message.</p>

<p class="minorText">This message was sent to you because you opted to watch the product "' . $__templater->escape($__vars['product']['title']) . '" at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' with email notification of updates.</p>

<p class="minorText">If you no longer wish to receive these emails, you may <a href="' . $__templater->func('link', array('canonical:email-stop/content', $__vars['xf']['toUser'], array('t' => 'dbtech_ecommerce_product', 'id' => $__vars['product']['product_id'], ), ), true) . '">disable emails from this product</a> or <a href="' . $__templater->func('link', array('canonical:email-stop/all', $__vars['xf']['toUser'], ), true) . '">disable all emails</a>.</p>' . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);