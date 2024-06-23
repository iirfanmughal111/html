<?php
// FROM HASH: 55f8abb3b4206191db5507f8e5c2e159
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['currencies'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block wallet"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
			<div class="block-body block-row">
				';
		if ($__templater->isTraversable($__vars['currencies'])) {
			foreach ($__vars['currencies'] AS $__vars['currency']) {
				$__finalCompiled .= '
					' . $__templater->callMacro('dbtech_credits_currency_macros', 'wallet', array(
					'currency' => $__vars['currency'],
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