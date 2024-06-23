<?php
// FROM HASH: 4e4508c0405951eff35995046390830a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['currencies'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">
				' . $__templater->escape($__vars['title']) . '
			</h3>
			<div class="block-body">
				';
		if ($__templater->isTraversable($__vars['currencies'])) {
			foreach ($__vars['currencies'] AS $__vars['currency']) {
				$__finalCompiled .= '
					' . $__templater->callMacro(null, 'dbtech_credits_currency_macros::richest', array(
					'currency' => $__vars['currency'],
					'limit' => $__vars['options']['limit'],
					'showAmounts' => $__vars['options']['showAmounts'],
				), $__vars) . '
				';
			}
		}
		$__finalCompiled .= '
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);