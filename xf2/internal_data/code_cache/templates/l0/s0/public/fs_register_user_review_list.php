<?php
// FROM HASH: 79c3cab2814c66fa0acf171070891fd7
return array(
'macros' => array('reviews_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'review' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
		<li class="block-row block-row--separated js-inlineModContainer" data-author="' . ($__templater->escape($__vars['review']['User']['username']) ?: $__templater->escape($__vars['review']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['review'], 'isVisible', array())) ? 'is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['review']['User'], 's', false, array(
		'defaultname' => $__vars['review']['username'],
	))) . '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('threads', $__vars['review'], ), true) . '">' . ($__templater->func('prefix', array('thread', $__vars['review'], ), true) . $__templater->func('highlight', array($__vars['review']['title'], $__vars['options']['term'], ), true)) . '</a>
			</h3>

			<div class="contentRow-snippet">' . ($__vars['review']['custom_fields']['overall_experience'] ? $__templater->escape($__vars['review']['custom_fields']['overall_experience']) : '') . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					<li>' . $__templater->func('username_link', array($__vars['review']['User'], false, array(
		'defaultname' => $__vars['review']['username'],
	))) . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['review']['post_date'], array(
	))) . '</li>
					<li>' . 'fs_register_location' . ' <a href="' . $__templater->func('link', array('forums', $__vars['post']['Thread']['Forum'], ), true) . '">' . $__templater->escape($__vars['review']['Forum']['title']) . '</a></li>
				</ul>
			</div>
		</div>
	</div>
</li>

';
	return $__finalCompiled;
}
)),
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
							' . $__templater->callMacro(null, 'reviews_list', array(
					'review' => $__vars['review'],
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
			'href' => $__templater->func('link', array('members/my-reviews', $__vars['user'], array('before_id' => $__vars['oldestItemId'], ), ), false),
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
					<div class="block-row">' . 'no_any_data_found' . '</div>
				</div>
			';
	}
	$__finalCompiled .= '
		
</div>



';
	return $__finalCompiled;
}
);