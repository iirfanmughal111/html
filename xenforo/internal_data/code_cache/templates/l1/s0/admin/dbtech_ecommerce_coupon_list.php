<?php
// FROM HASH: 04b097b0cc94142e18a5aed56b39ae1f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Coupons');
	$__finalCompiled .= '

';
	if ($__vars['xf']['options']['dbtechEcommerceCoupons']['enabled']) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add coupon', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/coupons/add', ), false),
			'icon' => 'add',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	if (!$__vars['xf']['options']['dbtechEcommerceCoupons']['enabled']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
			' . 'Coupons are currently globally disabled. You cannot add or edit coupons, and any current coupons will not apply.' . '
		</div>
	</div>
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('option_macros', 'option_form_block', array(
		'options' => $__vars['options'],
	), $__vars) . '

';
	if (!$__templater->test($__vars['coupons'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'dbtech-ecommerce/coupons',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['coupons'])) {
			foreach ($__vars['coupons'] AS $__vars['coupon']) {
				$__compilerTemp1 .= '
						';
				$__compilerTemp2 = '';
				if ($__vars['coupon']['coupon_state'] == 'visible') {
					$__compilerTemp2 .= '
										' . 'Valid from ' . $__templater->func('date_time', array($__vars['coupon']['start_date'], ), true) . ' until ' . $__templater->func('date_time', array($__vars['coupon']['expiry_date'], ), true) . '' . '
									';
				} else if ($__vars['coupon']['coupon_state'] == 'deleted') {
					$__compilerTemp2 .= '
										' . $__templater->callMacro('public:deletion_macros', 'notice', array(
						'log' => $__vars['coupon']['DeletionLog'],
					), $__vars) . '
									';
				}
				$__compilerTemp3 = array(array(
					'hash' => $__vars['coupon']['coupon_id'],
					'href' => ($__vars['xf']['options']['dbtechEcommerceCoupons']['enabled'] ? $__templater->func('link', array('dbtech-ecommerce/coupons/edit', $__vars['coupon'], ), false) : ''),
					'label' => $__templater->escape($__vars['coupon']['title']),
					'hint' => $__templater->escape($__vars['coupon']['coupon_code']),
					'explain' => '
									' . $__compilerTemp2 . '				
								',
					'_type' => 'main',
					'html' => '',
				)
,array(
					'class' => ($__vars['customPermissions'][$__vars['coupon']['coupon_id']] ? 'dataList-cell--highlighted' : ''),
					'href' => $__templater->func('link', array('dbtech-ecommerce/coupons/permissions', $__vars['coupon'], ), false),
					'_type' => 'action',
					'html' => '
								' . 'Permissions' . '
							',
				));
				if ($__vars['xf']['options']['dbtechEcommerceCoupons']['enabled']) {
					$__compilerTemp3[] = array(
						'href' => $__templater->func('link', array('dbtech-ecommerce/coupons/add', null, array('source_coupon_id' => $__vars['coupon']['coupon_id'], ), ), false),
						'_type' => 'action',
						'html' => '
									' . 'Copy' . '
								',
					);
				}
				$__compilerTemp3[] = array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/logs/coupons', null, array('criteria' => array('coupon_id' => $__vars['coupon']['coupon_id'], ), ), ), false),
					'class' => 'u-hideMedium',
					'_type' => 'action',
					'html' => 'View usage',
				);
				$__compilerTemp3[] = array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/coupons/delete', $__vars['coupon'], ), false),
					'_type' => 'delete',
					'html' => '',
				);
				$__compilerTemp1 .= $__templater->dataRow(array(
					'rowclass' => (($__vars['coupon']['coupon_state'] == 'deleted') ? 'dataList-row--deleted' : ''),
				), $__compilerTemp3) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['coupons'], ), true) . '</span>
			</div>
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	return $__finalCompiled;
}
);