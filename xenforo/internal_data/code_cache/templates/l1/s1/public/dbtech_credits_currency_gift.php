<?php
// FROM HASH: bef120aceaee05594e967605d7ede861
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['event']['title']));
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<ul class="block-body listPlain">
			' . $__templater->callMacro('dbtech_credits_currency_macros', 'purchase_option', array(
		'event' => $__vars['event'],
		'profiles' => $__vars['profiles'],
		'currency' => $__vars['currency'],
		'isGift' => true,
	), $__vars) . '
		</ul>
	</div>
</div>';
	return $__finalCompiled;
}
);