<?php
// FROM HASH: 67ad9674ed20819860815a4721fe2adb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['reviews'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		';
		if ($__vars['style'] == 'carousel') {
			$__finalCompiled .= '
			';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
			<h3 class="block-header">
				<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest reviews') . '</a>
			</h3>
			' . $__templater->callMacro('xa_sc_review_macros', 'reviews_carousel', array(
				'reviews' => $__vars['reviews'],
				'viewAllLink' => $__vars['link'],
			), $__vars) . '
		';
		} else {
			$__finalCompiled .= '
			<div class="block-container">
				';
			if ($__vars['style'] == 'full') {
				$__finalCompiled .= '
					<h3 class="block-header">
						<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest reviews') . '</a>
					</h3>
					<div class="block-body">
						<div class="structItemContainer">
							';
				if ($__templater->isTraversable($__vars['reviews'])) {
					foreach ($__vars['reviews'] AS $__vars['review']) {
						$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_review_macros', 'review', array(
							'review' => $__vars['review'],
							'item' => $__vars['review']['Item'],
							'showItem' => true,
							'showAttachments' => true,
						), $__vars) . '
							';
					}
				}
				$__finalCompiled .= '
						</div>
					</div>
					';
				if ($__vars['hasMore']) {
					$__finalCompiled .= '
						<div class="block-footer">
							<span class="block-footer-controls">
								' . $__templater->button('View more' . $__vars['xf']['language']['ellipsis'], array(
						'href' => $__vars['link'],
						'rel' => 'nofollow',
					), '', array(
					)) . '
							</span>
						</div>
					';
				}
				$__finalCompiled .= '
				';
			} else {
				$__finalCompiled .= '
					<h3 class="block-minorHeader">
						<a href="' . $__templater->func('link', array('showcase/latest-reviews', ), true) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest reviews') . '</a>
					</h3>
					<ul class="block-body">
						';
				if ($__templater->isTraversable($__vars['reviews'])) {
					foreach ($__vars['reviews'] AS $__vars['review']) {
						$__finalCompiled .= '
							<li class="block-row">
								' . $__templater->callMacro('xa_sc_review_macros', 'review_simple', array(
							'review' => $__vars['review'],
						), $__vars) . '
							</li>
						';
					}
				}
				$__finalCompiled .= '
					</ul>
				';
			}
			$__finalCompiled .= '
			</div>
		';
		}
		$__finalCompiled .= '
	</div>
';
	}
	return $__finalCompiled;
}
);