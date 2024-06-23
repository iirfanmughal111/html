<?php
// FROM HASH: 29c005cf0b7270e4bd0e63112a9b3766
return array(
'macros' => array('items_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'thread' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
		<li class="block-row block-row--separated js-inlineModContainer" data-author="' . ($__templater->escape($__vars['thread']['User']['username']) ?: $__templater->escape($__vars['thread']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['thread'], 'isVisible', array())) ? 'is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['thread']['User'], 's', false, array(
		'defaultname' => $__vars['thread']['username'],
	))) . '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('threads', $__vars['thread'], ), true) . '">' . ($__templater->func('prefix', array('thread', $__vars['thread'], ), true) . $__templater->func('highlight', array($__vars['thread']['title'], $__vars['options']['term'], ), true)) . '</a>
			</h3>
' . '
			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					<li>' . $__templater->func('username_link', array($__vars['thread']['User'], false, array(
		'defaultname' => $__vars['thread']['username'],
	))) . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['thread']['post_date'], array(
	))) . '</li>
					
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
	if (!$__templater->test($__vars['ads'], 'empty', array())) {
		$__finalCompiled .= '
				<ul class="block-body js-myAlertsTarget">
						';
		if ($__templater->isTraversable($__vars['ads'])) {
			foreach ($__vars['ads'] AS $__vars['ad']) {
				$__finalCompiled .= '
							' . $__templater->callMacro(null, 'items_list', array(
					'thread' => $__vars['ad'],
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
			'href' => $__templater->func('link', array('members/ads', $__vars['user'], array('before_id' => $__vars['oldestItemId'], ), ), false),
			'rel' => 'nofollow',
			'data-xf-click' => 'inserter',
			'data-append' => '.js-myAlertsTarget',
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
					<div class="block-row">' . 'Ads Not Found...' . '</div>
				</div>
			';
	}
	$__finalCompiled .= '
		
</div>



';
	return $__finalCompiled;
}
);