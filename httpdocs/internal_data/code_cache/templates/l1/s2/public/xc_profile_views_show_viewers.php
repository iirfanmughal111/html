<?php
// FROM HASH: dece3ec2b12f5b2ff6f29bb6e032f24d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Users who viewed your profile');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
		';
	if (!$__templater->test($__vars['userViewers'], 'empty', array())) {
		$__finalCompiled .= '
			<ul class="block-body js-viewersTarget">
				';
		if ($__templater->isTraversable($__vars['userViewers'])) {
			foreach ($__vars['userViewers'] AS $__vars['viewer']) {
				$__finalCompiled .= '
					<li class="block-row block-row--separated">
						';
				$__vars['extraData'] = $__templater->preEscaped($__templater->func('date_dynamic', array($__vars['viewer']['view_date'], array(
				))));
				$__finalCompiled .= '

						' . $__templater->callMacro('member_list_macros', 'item', array(
					'user' => $__vars['viewer']['Visitor'],
					'extraData' => $__vars['extraData'],
				), $__vars) . '
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ul>
			<div class="block-footer js-viewersLoadMore">
				<span class="block-footer-controls">' . $__templater->button('
					' . 'Show older items' . '
					', array(
			'href' => $__templater->func('link', array('members/show-viewers', $__vars['user'], array('before_id' => $__vars['oldestItemId'], ), ), false),
			'data-xf-click' => 'inserter',
			'data-append' => '.js-viewersTarget',
			'data-replace' => '.js-viewersLoadMore',
		), '', array(
		)) . '</span>
			</div>
		';
	} else if ($__vars['beforeId']) {
		$__finalCompiled .= '
			<div class="block-body js-viewersTarget">
				<div class="block-row block-row--separated">' . 'There are no more items to show.' . '</div>
			</div>
		';
	} else {
		$__finalCompiled .= '
			<div class="block-body block-row">
				<div class="block-row js-viewersTarget">
					' . 'No one has viewed your profile.' . '
				</div>
			</div>
		';
	}
	$__finalCompiled .= '
    </div>
</div>';
	return $__finalCompiled;
}
);