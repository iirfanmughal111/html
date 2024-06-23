<?php
// FROM HASH: 58b72912ff2744227832fd1ecca764e6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Discounts');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add discount', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/discounts/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['discounts'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'dbtech-ecommerce/discounts',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['discounts'])) {
			foreach ($__vars['discounts'] AS $__vars['discount']) {
				$__compilerTemp1 .= '
						';
				$__compilerTemp2 = '';
				if ($__vars['discount']['discount_state'] == 'visible') {
					$__compilerTemp2 .= '
										' . 'Minimum cart value: ' . $__templater->filter($__vars['discount']['discount_threshold'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '' . '
									';
				} else if ($__vars['discount']['discount_state'] == 'deleted') {
					$__compilerTemp2 .= '
										' . $__templater->callMacro('public:deletion_macros', 'notice', array(
						'log' => $__vars['discount']['DeletionLog'],
					), $__vars) . '
									';
				}
				$__compilerTemp1 .= $__templater->dataRow(array(
					'rowclass' => (($__vars['discount']['discount_state'] == 'deleted') ? 'dataList-row--deleted' : ''),
				), array(array(
					'hash' => $__vars['discount']['discount_id'],
					'href' => $__templater->func('link', array('dbtech-ecommerce/discounts/edit', $__vars['discount'], ), false),
					'label' => $__templater->escape($__vars['discount']['title']),
					'hint' => $__templater->func('number', array($__vars['discount']['discount_percent'], 2, ), true) . '%',
					'explain' => '
									' . $__compilerTemp2 . '				
								',
					'_type' => 'main',
					'html' => '',
				),
				array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/discounts/add', null, array('source_discount_id' => $__vars['discount']['discount_id'], ), ), false),
					'_type' => 'action',
					'html' => '
								' . 'Copy' . '
							',
				),
				array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/discounts/delete', $__vars['discount'], ), false),
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
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['discounts'], ), true) . '</span>
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