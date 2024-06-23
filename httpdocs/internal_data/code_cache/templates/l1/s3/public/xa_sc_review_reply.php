<?php
// FROM HASH: 06c1fe5668e2dfe02e06910f328f4722
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('xa_sc_review_macros', 'reply', array(
		'reply' => $__vars['reply'],
		'review' => $__vars['review'],
	), $__vars) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);