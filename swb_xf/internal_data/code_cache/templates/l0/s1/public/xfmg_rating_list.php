<?php
// FROM HASH: 60213e6a938263396a1a35005e8e881d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Members who rated this');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['content'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container js-ratingList-' . $__templater->escape($__templater->method($__vars['content'], 'getEntityId', array())) . '">
		<ol class="block-body">
			';
	if ($__templater->isTraversable($__vars['ratings'])) {
		foreach ($__vars['ratings'] AS $__vars['rating']) {
			$__finalCompiled .= '
				<li class="block-row block-row--separated">
					';
			$__vars['extraData'] = $__templater->preEscaped('
						' . $__templater->callMacro('rating_macros', 'rating', array(
				'row' => false,
				'readOnly' => 'true',
				'currentRating' => $__vars['rating']['rating'],
			), $__vars) . '
					');
			$__finalCompiled .= '

					' . $__templater->callMacro('member_list_macros', 'item', array(
				'user' => $__vars['rating']['User'],
				'extraData' => $__templater->filter($__vars['extraData'], array(array('raw', array()),), false),
			), $__vars) . '
				</li>
			';
		}
	}
	$__finalCompiled .= '
		</ol>
		';
	if ($__vars['hasNext']) {
		$__finalCompiled .= '
			<div class="block-footer">
				<span class="block-footer-controls">
					' . $__templater->button('Continue' . $__vars['xf']['language']['ellipsis'], array(
			'href' => $__templater->func('link', array($__vars['linkPrefix'] . '/ratings', $__vars['content'], array('page' => $__vars['page'] + 1, ), ), false),
			'data-xf-click' => 'inserter',
			'data-replace' => '.js-ratingList-' . $__templater->method($__vars['content'], 'getEntityId', array()),
			'data-scroll-target' => '< .overlay',
		), '', array(
		)) . '
				</span>
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>';
	return $__finalCompiled;
}
);