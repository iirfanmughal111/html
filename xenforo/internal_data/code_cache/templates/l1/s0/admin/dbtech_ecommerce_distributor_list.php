<?php
// FROM HASH: 8fc2767d9af76a8dd80f3b4c718351b3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Distributors');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped('Distributors can generate licenses for products they have been assigned.');
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add distributor', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/distributors/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

<div class="block">
	<div class="block-outer">
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => 'dbtech-ecommerce/distributors',
		'class' => 'block-outer-opposite',
	), $__vars) . '
	</div>
	<div class="block-container">
		<div class="block-body">
			';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['distributors'])) {
		foreach ($__vars['distributors'] AS $__vars['distributor']) {
			$__compilerTemp1 .= '
					' . $__templater->dataRow(array(
			), array(array(
				'hash' => $__vars['distributor']['user_id'],
				'href' => $__templater->func('link', array('dbtech-ecommerce/distributors/edit', $__vars['distributor'], ), false),
				'label' => $__templater->escape($__vars['distributor']['User']['username']),
				'explain' => '
								<ul class="listInline listInline--bullet">
									<li>' . '' . $__templater->filter($__templater->func('count', array($__vars['distributor']['available_products'], ), false), array(array('number', array()),), true) . ' available products' . '</li>
								</ul>
							',
				'_type' => 'main',
				'html' => '',
			),
			array(
				'class' => 'dataList-cell--action',
				'label' => 'View' . $__vars['xf']['language']['ellipsis'],
				'_type' => 'popup',
				'html' => '

							<div class="menu" data-menu="menu" aria-hidden="true">
								<div class="menu-content">
									<h3 class="menu-header">' . 'View' . $__vars['xf']['language']['ellipsis'] . '</h3>
									<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/distributors', null, array('criteria' => array('distributor_id' => $__vars['distributor']['user_id'], ), ), ), true) . '" class="menu-linkRow">' . 'View log' . '</a>
								</div>
							</div>
						',
			),
			array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/distributors/delete', $__vars['distributor'], ), false),
				'tooltip' => 'Delete' . ' ',
				'_type' => 'delete',
				'html' => '',
			))) . '
				';
		}
	}
	$__finalCompiled .= $__templater->dataList('
				' . $__compilerTemp1 . '
			', array(
	)) . '
		</div>
		<div class="block-footer">
			<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['distributors'], ), true) . '</span>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);