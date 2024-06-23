<?php
// FROM HASH: 649c9f9823b4a1394881beb132a91f21
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
				if ($__vars['currency']['sidebar']) {
					$__finalCompiled .= '
	<dl class="pairs pairs--rows pairs--rows--centered fauxBlockLink">
		<dt title="' . $__templater->escape($__vars['currency']['title']) . '">' . $__templater->escape($__vars['currency']['title']) . '</dt>
		<dd>
			<a href="' . $__templater->func('link', array('dbtech-credits/currency', $__vars['currency'], array('user_id' => $__vars['user']['user_id'], ), ), true) . '" data-xf-click="overlay" class="fauxBlockLink-blockLink u-concealed">
				' . $__templater->escape($__vars['currency']['prefix']) . $__templater->escape($__templater->method($__vars['currency'], 'getValueFromUser', array($__vars['user'], ))) . $__templater->escape($__vars['currency']['suffix']) . '
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