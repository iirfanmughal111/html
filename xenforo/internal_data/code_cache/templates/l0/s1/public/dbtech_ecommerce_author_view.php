<?php
// FROM HASH: 9fa6424e885210bd7ae99a2aa5ac87c5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Products by ' . $__templater->escape($__vars['user']['username']) . '');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canAddDbtechEcommerceProduct', array())) {
			$__compilerTemp1 .= '
				<h3 class="menu-header">' . 'Add product' . '</h3>

				';
			$__compilerTemp2 = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductTypeHandlers', array());
			if ($__templater->isTraversable($__compilerTemp2)) {
				foreach ($__compilerTemp2 AS $__vars['productType'] => $__vars['handler']) {
					$__compilerTemp1 .= '
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/add', null, array('product_type' => $__vars['productType'], ), ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . $__templater->escape($__templater->method($__vars['handler'], 'getProductTypePhrase', array())) . '</a>
				';
				}
			}
			$__compilerTemp1 .= '
			';
		}
		$__compilerTemp3 = '';
		if (!$__templater->test($__vars['user']['DBTechEcommerceCommission'], 'empty', array()) AND $__templater->method($__vars['xf']['visitor'], 'canViewDbtechEcommerceIncomeStats', array())) {
			$__compilerTemp3 .= '
				<h3 class="menu-header">' . 'Income statistics' . '</h3>

				<a href="' . $__templater->func('link', array('dbtech-ecommerce/authors/income-stats', $__vars['user'], ), true) . '" class="menu-linkRow">' . 'View income statistics' . '</a>
			';
		}
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Manage' . $__vars['xf']['language']['ellipsis'], array(
			'class' => 'menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
		), '', array(
		)) . '
	<div class="menu" data-menu="menu" aria-hidden="true">
		<div class="menu-content">
			' . $__compilerTemp1 . '
			' . $__compilerTemp3 . '
		</div>
	</div>
');
	}
	$__finalCompiled .= '

';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['products'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="dbtech_ecommerce_product" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
		<div class="block-outer">';
		$__compilerTemp4 = '';
		$__compilerTemp5 = '';
		$__compilerTemp5 .= '
							';
		if ($__vars['canInlineMod']) {
			$__compilerTemp5 .= '
								' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
							';
		}
		$__compilerTemp5 .= '
						';
		if (strlen(trim($__compilerTemp5)) > 0) {
			$__compilerTemp4 .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp5 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= $__templater->func('trim', array('

			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'dbtech-ecommerce/authors',
			'data' => $__vars['user'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			' . $__compilerTemp4 . '

		'), false) . '</div>

		<div class="block-container">
			<div class="block-body">
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['products'])) {
			foreach ($__vars['products'] AS $__vars['product']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('dbtech_ecommerce_product_list_macros', 'product', array(
					'product' => $__vars['product'],
					'allowInlineMod' => $__vars['canInlineMod'],
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			</div>
		</div>

		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'dbtech-ecommerce/authors',
			'data' => $__vars['user'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">
		';
		if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
			$__finalCompiled .= '
			' . 'You have not posted any products yet.' . '
		';
		} else {
			$__finalCompiled .= '
			' . '' . $__templater->escape($__vars['user']['username']) . ' has not posted any products yet.' . '
		';
		}
		$__finalCompiled .= '
	</div>
';
	}
	return $__finalCompiled;
}
);