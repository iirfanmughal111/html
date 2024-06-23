<?php
// FROM HASH: 93472bd3e4d00820c0d59b1d596e083d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Unlocked content');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__templater->includeCss('search_results.less');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'robots', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<ol class="block-body">
			';
	if ($__templater->isTraversable($__vars['results'])) {
		foreach ($__vars['results'] AS $__vars['result']) {
			$__finalCompiled .= '
				' . $__templater->filter($__templater->method($__vars['result'], 'render', array()), array(array('raw', array()),), true) . '
			';
		}
	}
	$__finalCompiled .= '
		</ol>
		';
	if ($__vars['getOlderResultsDate']) {
		$__finalCompiled .= '
			<div class="block-footer">
				<span class="block-footer-controls">' . $__templater->button('
					' . 'View older results' . '
				', array(
			'href' => $__templater->func('link', array('dbtech-credits/charge/unlocked/older', null, array('before' => $__vars['getOlderResultsDate'], ), ), false),
			'class' => 'button--link',
		), '', array(
		)) . '</span>
			</div>
		';
	}
	$__finalCompiled .= '
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-credits/charge/unlocked',
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);