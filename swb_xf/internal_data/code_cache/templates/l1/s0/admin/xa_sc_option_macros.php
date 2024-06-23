<?php
// FROM HASH: 2ce1e88366060d8901bed8d8c39da308
return array(
'macros' => array('option_form_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '',
		'options' => '!',
		'containerBeforeHtml' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__vars['hundred'] = '0';
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['options'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp1 .= '
						';
				if ($__vars['option']['Relations']['xaShowcase']['display_order'] < 1000) {
					$__compilerTemp1 .= '
							';
					if ($__vars['group']) {
						$__compilerTemp1 .= '
								';
						$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
						$__compilerTemp1 .= '
								';
						if (($__vars['curHundred'] > $__vars['hundred'])) {
							$__compilerTemp1 .= '
									';
							$__vars['hundred'] = $__vars['curHundred'];
							$__compilerTemp1 .= '
									<hr class="formRowSep" />
								';
						}
						$__compilerTemp1 .= '
							';
					}
					$__compilerTemp1 .= '
							' . $__templater->callMacro('option_macros', 'option_row', array(
						'group' => $__vars['group'],
						'option' => $__vars['option'],
					), $__vars) . '
						';
				}
				$__compilerTemp1 .= '
					';
			}
		}
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp2 .= '
						';
				if (($__vars['option']['Relations']['xaShowcase']['display_order'] >= 1000) AND ($__vars['option']['Relations']['xaShowcase']['display_order'] < 2000)) {
					$__compilerTemp2 .= '
							';
					if ($__vars['group']) {
						$__compilerTemp2 .= '
								';
						$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
						$__compilerTemp2 .= '
								';
						if (($__vars['curHundred'] > $__vars['hundred'])) {
							$__compilerTemp2 .= '
									';
							$__vars['hundred'] = $__vars['curHundred'];
							$__compilerTemp2 .= '
									<hr class="formRowSep" />
								';
						}
						$__compilerTemp2 .= '
							';
					}
					$__compilerTemp2 .= '
							' . $__templater->callMacro('option_macros', 'option_row', array(
						'group' => $__vars['group'],
						'option' => $__vars['option'],
					), $__vars) . '
						';
				}
				$__compilerTemp2 .= '
					';
			}
		}
		$__compilerTemp3 = '';
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp3 .= '
						';
				if (($__vars['option']['Relations']['xaShowcase']['display_order'] >= 2000) AND ($__vars['option']['Relations']['xaShowcase']['display_order'] < 3000)) {
					$__compilerTemp3 .= '
							';
					if ($__vars['group']) {
						$__compilerTemp3 .= '
								';
						$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
						$__compilerTemp3 .= '
								';
						if (($__vars['curHundred'] > $__vars['hundred'])) {
							$__compilerTemp3 .= '
									';
							$__vars['hundred'] = $__vars['curHundred'];
							$__compilerTemp3 .= '
									<hr class="formRowSep" />
								';
						}
						$__compilerTemp3 .= '
							';
					}
					$__compilerTemp3 .= '
							' . $__templater->callMacro('option_macros', 'option_row', array(
						'group' => $__vars['group'],
						'option' => $__vars['option'],
					), $__vars) . '
						';
				}
				$__compilerTemp3 .= '
					';
			}
		}
		$__compilerTemp4 = '';
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp4 .= '
						';
				if (($__vars['option']['Relations']['xaShowcase']['display_order'] >= 3000) AND ($__vars['option']['Relations']['xaShowcase']['display_order'] < 4000)) {
					$__compilerTemp4 .= '
							';
					if ($__vars['group']) {
						$__compilerTemp4 .= '
								';
						$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
						$__compilerTemp4 .= '
								';
						if (($__vars['curHundred'] > $__vars['hundred'])) {
							$__compilerTemp4 .= '
									';
							$__vars['hundred'] = $__vars['curHundred'];
							$__compilerTemp4 .= '
									<hr class="formRowSep" />
								';
						}
						$__compilerTemp4 .= '
							';
					}
					$__compilerTemp4 .= '
							' . $__templater->callMacro('option_macros', 'option_row', array(
						'group' => $__vars['group'],
						'option' => $__vars['option'],
					), $__vars) . '
						';
				}
				$__compilerTemp4 .= '
					';
			}
		}
		$__compilerTemp5 = '';
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp5 .= '
						';
				if (($__vars['option']['Relations']['xaShowcase']['display_order'] >= 4000) AND ($__vars['option']['Relations']['xaShowcase']['display_order'] < 5000)) {
					$__compilerTemp5 .= '
							';
					if ($__vars['group']) {
						$__compilerTemp5 .= '
								';
						$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
						$__compilerTemp5 .= '
								';
						if (($__vars['curHundred'] > $__vars['hundred'])) {
							$__compilerTemp5 .= '
									';
							$__vars['hundred'] = $__vars['curHundred'];
							$__compilerTemp5 .= '
									<hr class="formRowSep" />
								';
						}
						$__compilerTemp5 .= '
							';
					}
					$__compilerTemp5 .= '
							' . $__templater->callMacro('option_macros', 'option_row', array(
						'group' => $__vars['group'],
						'option' => $__vars['option'],
					), $__vars) . '
						';
				}
				$__compilerTemp5 .= '
					';
			}
		}
		$__compilerTemp6 = '';
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp6 .= '
						';
				if (($__vars['option']['Relations']['xaShowcase']['display_order'] >= 5000) AND ($__vars['option']['Relations']['xaShowcase']['display_order'] < 6000)) {
					$__compilerTemp6 .= '
							';
					if ($__vars['group']) {
						$__compilerTemp6 .= '
								';
						$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
						$__compilerTemp6 .= '
								';
						if (($__vars['curHundred'] > $__vars['hundred'])) {
							$__compilerTemp6 .= '
									';
							$__vars['hundred'] = $__vars['curHundred'];
							$__compilerTemp6 .= '
									<hr class="formRowSep" />
								';
						}
						$__compilerTemp6 .= '
							';
					}
					$__compilerTemp6 .= '
							' . $__templater->callMacro('option_macros', 'option_row', array(
						'group' => $__vars['group'],
						'option' => $__vars['option'],
					), $__vars) . '
						';
				}
				$__compilerTemp6 .= '
					';
			}
		}
		$__compilerTemp7 = '';
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp7 .= '
						';
				if ($__vars['option']['Relations']['xaShowcase']['display_order'] >= 6000) {
					$__compilerTemp7 .= '
							';
					if ($__vars['group']) {
						$__compilerTemp7 .= '
								';
						$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
						$__compilerTemp7 .= '
								';
						if (($__vars['curHundred'] > $__vars['hundred'])) {
							$__compilerTemp7 .= '
									';
							$__vars['hundred'] = $__vars['curHundred'];
							$__compilerTemp7 .= '
									<hr class="formRowSep" />
								';
						}
						$__compilerTemp7 .= '
							';
					}
					$__compilerTemp7 .= '
							' . $__templater->callMacro('option_macros', 'option_row', array(
						'group' => $__vars['group'],
						'option' => $__vars['option'],
					), $__vars) . '
						';
				}
				$__compilerTemp7 .= '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
			' . $__templater->filter($__vars['containerBeforeHtml'], array(array('raw', array()),), true) . '
			<div class="block-container">
				<h3 class="block-formSectionHeader">
					' . 'General options' . '
				</h3>
				<div class="block-body">
					' . $__compilerTemp1 . '
				</div>

				<h3 class="block-formSectionHeader">
					' . 'Item list options' . '
				</h3>               
				<div class="block-body">
					' . $__compilerTemp2 . '
				</div>

				<h3 class="block-formSectionHeader">
					' . 'Item page options' . '
				</h3>               
				<div class="block-body">
					' . $__compilerTemp3 . '
				</div>

				<h3 class="block-formSectionHeader">
					' . 'Series options' . '
				</h3>               
				<div class="block-body">
					' . $__compilerTemp4 . '
				</div>

				<h3 class="block-formSectionHeader">
					' . 'Map options' . '
				</h3>               
				<div class="block-body">
					' . $__compilerTemp5 . '
				</div>

				<h3 class="block-formSectionHeader">
					' . 'Auto feature &amp; unfeature options ' . '
				</h3>               
				<div class="block-body">
					' . $__compilerTemp6 . '
				</div>

				<h3 class="block-formSectionHeader">
					' . 'Misc options ' . '
				</h3>               
				<div class="block-body">
					' . $__compilerTemp7 . '
				</div>
				' . $__templater->formSubmitRow(array(
			'sticky' => 'true',
			'icon' => 'save',
		), array(
		)) . '
			</div>
		', array(
			'action' => $__templater->func('link', array('options/update', ), false),
			'ajax' => 'true',
			'class' => 'block',
		)) . '
	';
	}
	$__finalCompiled .= '
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