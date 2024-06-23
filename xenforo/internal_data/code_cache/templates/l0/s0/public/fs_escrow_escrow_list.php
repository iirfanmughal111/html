<?php
// FROM HASH: 63cc9b24522a4a667f15de9a76f54a32
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-container">
		
		';
	if (!$__templater->test($__vars['escrows'], 'empty', array())) {
		$__finalCompiled .= '
			';
		if ($__vars['type'] == 'my') {
			$__finalCompiled .= '
				<ul class="block-body js-escrowMyTarget">
						';
			if ($__templater->isTraversable($__vars['escrows'])) {
				foreach ($__vars['escrows'] AS $__vars['escrow']) {
					$__finalCompiled .= '
							' . $__templater->callMacro('fs_escrow_list_macro', 'listing', array(
						'listing' => $__vars['escrow'],
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
				'href' => $__templater->func('link', array('members/my-escrow', $__vars['user'], array('before_id' => $__vars['oldestItemId'], ), ), false),
				'rel' => 'nofollow',
				'data-xf-click' => 'inserter',
				'data-append' => '.js-escrowMyTarget',
				'data-replace' => '.js-myLoadMore',
			), '', array(
			)) . '</span>
				</div>
				';
		} else if ($__vars['type'] == 'mentioned') {
			$__finalCompiled .= '
				<ul class="block-body js-escrowMentionedTarget">
						';
			if ($__templater->isTraversable($__vars['escrows'])) {
				foreach ($__vars['escrows'] AS $__vars['escrow']) {
					$__finalCompiled .= '
							' . $__templater->callMacro('fs_escrow_list_macro', 'listing', array(
						'listing' => $__vars['escrow'],
					), $__vars) . '
						';
				}
			}
			$__finalCompiled .= '	
				</ul>
				<div class="block-footer js-mentionedLoadMore">
					<span class="block-footer-controls">' . $__templater->button('
							' . 'Show older items' . '
						', array(
				'href' => $__templater->func('link', array('members/mentioned-escrow', $__vars['user'], array('before_id' => $__vars['oldestItemId'], ), ), false),
				'rel' => 'nofollow',
				'data-xf-click' => 'inserter',
				'data-append' => '.js-escrowMentionedTarget',
				'data-replace' => '.js-mentionedLoadMore',
			), '', array(
			)) . '</span>
					</div>
				';
		}
		$__finalCompiled .= '
			';
	} else if ($__vars['beforeId']) {
		$__finalCompiled .= '
				<div class="block-body ' . $__templater->escape($__vars['type']) . '==\'my\' ? \'js-myLoadMore\' : \'js-mentionedLoadMore\'">
					<div class="block-row block-row--separated">' . 'There are no more items to show.' . '</div>
				</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-body ' . $__templater->escape($__vars['type']) . '==\'my\' ? \'js-myLoadMore\' : \'js-mentionedLoadMore\' ">
					<div class="block-row">' . 'No reviews Found' . '</div>
				</div>
			';
	}
	$__finalCompiled .= '
		
</div>';
	return $__finalCompiled;
}
);