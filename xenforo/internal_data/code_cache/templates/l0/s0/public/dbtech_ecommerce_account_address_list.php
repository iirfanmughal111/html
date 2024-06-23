<?php
// FROM HASH: b6e3fce800c3bf39ef76be54ade43a01
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Address book');
	$__finalCompiled .= '

';
	$__templater->includeCss('dbtech_ecommerce.less');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add address', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/account/address-book/add', ), false),
		'icon' => 'add',
		'overlay' => 'true',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['addresses'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['addresses'])) {
			foreach ($__vars['addresses'] AS $__vars['address']) {
				$__compilerTemp1 .= '
						';
				$__compilerTemp2 = '';
				if ($__vars['xf']['options']['dbtechEcommerceSalesTax']['enabled'] AND ($__vars['xf']['options']['dbtechEcommerceSalesTax']['enableVat'] AND $__vars['address']['sales_tax_id'])) {
					$__compilerTemp2 .= '
										' . $__templater->escape($__vars['address']['sales_tax_id']) . ' -
									';
				}
				$__compilerTemp3 = array(array(
					'href' => ($__templater->method($__vars['address'], 'canEdit', array()) ? $__templater->func('link', array('dbtech-ecommerce/account/address-book/edit', $__vars['address'], ), false) : $__templater->func('link', array('dbtech-ecommerce/account/address-book/view', $__vars['address'], ), false)),
					'class' => ($__vars['address']['is_default'] ? 'dataList-cell--highlighted' : ''),
					'label' => $__templater->escape($__vars['address']['title']),
					'hint' => '
									' . $__compilerTemp2 . '
									' . $__templater->escape($__vars['address']['business_title']) . '
								',
					'explain' => $__templater->escape($__vars['address']['Country']['name']),
					'_type' => 'main',
					'html' => '',
				)
,array(
					'class' => ($__vars['address']['is_default'] ? 'dataList-cell--highlighted' : '') . ' dataList-cell--action u-hideMedium',
					'label' => 'View' . $__vars['xf']['language']['ellipsis'],
					'_type' => 'popup',
					'html' => '

								<div class="menu" data-menu="menu" aria-hidden="true">
									<div class="menu-content">
										<h3 class="menu-header">' . 'View' . $__vars['xf']['language']['ellipsis'] . '</h3>
										<a href="' . $__templater->func('link', array('dbtech-ecommerce/account', null, array('address' => $__vars['address']['address_id'], ), ), true) . '" class="menu-linkRow">' . 'Orders' . '</a>
									</div>
								</div>
							',
				));
				if ($__templater->method($__vars['address'], 'canDelete', array())) {
					$__compilerTemp3[] = array(
						'href' => $__templater->func('link', array('dbtech-ecommerce/account/address-book/delete', $__vars['address'], ), false),
						'tooltip' => 'Delete',
						'class' => ($__vars['address']['is_default'] ? 'dataList-cell--highlighted' : ''),
						'_type' => 'delete',
						'html' => '',
					);
				} else {
					$__compilerTemp3[] = array(
						'class' => ($__vars['address']['is_default'] ? 'dataList-cell--highlighted' : '') . ' dataList-cell--alt',
						'_type' => 'cell',
						'html' => '&nbsp;',
					);
				}
				$__compilerTemp1 .= $__templater->dataRow(array(
				), $__compilerTemp3) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					<tbody class="dataList-rowGroup">
					' . $__compilerTemp1 . '
					</tbody>
				', array(
		)) . '
			</div>
		</div>

	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">
		' . 'You have not added any addresses to your address book yet.' . '
	</div>
';
	}
	return $__finalCompiled;
}
);