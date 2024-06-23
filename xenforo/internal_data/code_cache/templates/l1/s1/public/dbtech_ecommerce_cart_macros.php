<?php
// FROM HASH: f1cd790221c54e0c723e8bcccbcd9625
return array(
'macros' => array('row' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['item']['Product'], 'xs', $__templater->func('link', array('dbtech-ecommerce', $__vars['item']['Product'], ), false), ), true) . '
		</div>
		<div class="contentRow-main contentRow-main--close">
			<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['item']['Product'], ), true) . '">' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['item']['Product'], ), true) . $__templater->escape($__vars['item']['Product']['title']) . '</a>
			<div class="contentRow-minor contentRow-minor--smaller">
				';
	if ($__templater->method($__vars['item']['Product'], 'hasLicenseFunctionality', array())) {
		$__finalCompiled .= '
					';
		if ($__templater->method($__vars['item']['Cost'], 'isLifetime', array())) {
			$__finalCompiled .= '
						' . '' . $__templater->filter($__vars['item']['price'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . ' - Lifetime' . '
					';
		} else {
			$__finalCompiled .= '
						' . '' . $__templater->filter($__vars['item']['price'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . ' - Expires ' . $__templater->func('date_time', array($__templater->method($__vars['item']['Cost'], 'getNewExpiryDate', array($__vars['item']['License'], )), ), true) . '' . '
					';
		}
		$__finalCompiled .= '
				';
	} else {
		$__finalCompiled .= '
					' . '' . $__templater->filter($__vars['item']['price'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . ' - ' . $__templater->escape($__vars['item']['quantity']) . 'x ' . $__templater->escape($__vars['item']['Cost']['title']) . '' . '
				';
	}
	$__finalCompiled .= '
			</div>
			';
	if ($__templater->method($__vars['item']['Product'], 'hasLicenseFunctionality', array()) AND $__vars['item']['License']) {
		$__finalCompiled .= '
				<div class="contentRow-minor contentRow-minor--smaller">
					' . $__templater->escape($__vars['item']['License']['license_key']) . '
				</div>
			';
	}
	$__finalCompiled .= '
			<div class="contentRow-minor contentRow-minor--smaller">
				<a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/remove-item', $__vars['item'], ), true) . '" data-xf-click="overlay">' . 'Remove from cart' . '</a>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);