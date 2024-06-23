<?php
// FROM HASH: fe9845ea35579a1b174b00f8727a18eb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
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

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="sc_update" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('xa_sc_update_macros', 'update', array(
		'update' => $__vars['update'],
		'item' => $__vars['item'],
		'showItem' => true,
	), $__vars) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);