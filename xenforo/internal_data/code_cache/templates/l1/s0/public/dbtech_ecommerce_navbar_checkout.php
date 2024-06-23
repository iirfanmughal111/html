<?php
// FROM HASH: 6380e60a3119b401c711a6f64b7a1206
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasOption', array('hasDbEcommerce', )) AND ($__templater->method($__vars['xf']['visitor'], 'canViewDbtechEcommerceProducts', array()) AND $__templater->method($__vars['xf']['visitor'], 'canPurchaseDbtechEcommerceProducts', array()))) {
		$__finalCompiled .= '
	<a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout', ), true) . '"
	   class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--dbtechEcommerceCart js-badge--dbtechEcommerceCart badgeContainer' . (($__templater->func('callable', array($__vars['xf']['visitor'], 'getDbtechEcommerceCartItems', ), false) ? $__templater->method($__vars['xf']['visitor'], 'getDbtechEcommerceCartItems', array()) : 0) ? ' badgeContainer--highlighted' : '') . '"
	   data-badge="' . ($__templater->func('callable', array($__vars['xf']['visitor'], 'getDbtechEcommerceCartItems', ), false) ? $__templater->func('number', array($__templater->method($__vars['xf']['visitor'], 'getDbtechEcommerceCartItems', array()), ), true) : 0) . '"
	   data-xf-click="menu"
	   data-xf-key="' . $__templater->filter('#', array(array('for_attr', array()),), true) . '"
	   data-menu-pos-ref="< .p-navgroup"
	   aria-label="' . 'Cart' . '"
	   aria-expanded="false"
	   aria-haspopup="true">
		<i aria-hidden="true"></i>
		<span class="p-navgroup-linkText">' . 'Cart' . '</span>
	</a>
	<div class="menu menu--structural menu--medium" data-menu="menu" aria-hidden="true"
		 data-href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/cart-popup', ), true) . '"
		 data-nocache="true"
		 data-load-target=".js-dbtechEcommerceCartMenuBody">
		<div class="menu-content">
			<h3 class="menu-header">' . 'Cart' . '</h3>
			<div class="js-dbtechEcommerceCartMenuBody">
				<div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);