<?php
// FROM HASH: 1d1639f719bc7e81feb976eb699ce62b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-container">
		';
	if (!$__templater->test($__vars['reviews'], 'empty', array())) {
		$__finalCompiled .= '
				<ul class="block-body js-myReviewsTarget">
						';
		if ($__templater->isTraversable($__vars['reviews'])) {
			foreach ($__vars['reviews'] AS $__vars['review']) {
				$__finalCompiled .= '
						
								' . $__templater->callMacro('fs_register_user_alert_list', 'items_list', array(
					'thread' => $__vars['review'],
				), $__vars) . '
						';
			}
		}
		$__finalCompiled .= '	
				</ul>
				<div class="block-footer js-myLoadMore">
					<span class="block-footer-controls">' . $__templater->button('
							' . 'Show older items' . '
						', array(
			'href' => $__templater->func('link', array('members/reviews', $__vars['user'], array('before_id' => $__vars['oldestItemId'], ), ), false),
			'rel' => 'nofollow',
			'data-xf-click' => 'inserter',
			'data-append' => '.js-myReviewsTarget',
			'data-replace' => '.js-myLoadMore',
		), '', array(
		)) . '</span>
				</div>
			';
	} else if ($__vars['beforeId']) {
		$__finalCompiled .= '
				<div class="block-body ">
					<div class="block-row block-row--separated">' . 'There are no more items to show.' . '</div>
				</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-body  ">
					<div class="block-row">' . 'No reviews Found' . '</div>
				</div>
			';
	}
	$__finalCompiled .= '
		
</div>';
	return $__finalCompiled;
}
);