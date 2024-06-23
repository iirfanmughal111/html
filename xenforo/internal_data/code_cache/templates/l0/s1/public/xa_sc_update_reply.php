<?php
// FROM HASH: 9b5d1931c9dc6120fa14ee272c65be08
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('xa_sc_update_macros', 'reply', array(
		'reply' => $__vars['reply'],
		'update' => $__vars['itemUpdate'],
	), $__vars) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);