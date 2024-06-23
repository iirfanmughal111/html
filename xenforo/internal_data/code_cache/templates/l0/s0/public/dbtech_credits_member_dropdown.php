<?php
// FROM HASH: 92da8142afa7768054216c52a1b669d9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['viewableCurrencies'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\Credits:Currency', )), 'getCurrencies', array(true, ));
	$__finalCompiled .= '

';
	if ($__vars['viewableCurrencies']) {
		$__finalCompiled .= '
	';
		if ($__templater->isTraversable($__vars['viewableCurrencies'])) {
			foreach ($__vars['viewableCurrencies'] AS $__vars['currency']) {
				if ($__vars['currency']['member_dropdown']) {
					$__finalCompiled .= '
		<dl class="pairs pairs--justified fauxBlockLink">
			<dt>' . $__templater->escape($__vars['currency']['title']) . '</dt>
			<dd>
				<a href="' . $__templater->func('link', array('dbtech-credits/currency', $__vars['currency'], ), true) . '" data-xf-click="overlay" class="fauxBlockLink-linkRow u-concealed">
					' . $__templater->escape($__vars['currency']['prefix']) . $__templater->escape($__templater->method($__vars['currency'], 'getValueFromUser', array($__vars['xf']['visitor'], ))) . $__templater->escape($__vars['currency']['suffix']) . '
				</a>
			</dd>
		</dl>
	';
				}
			}
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);