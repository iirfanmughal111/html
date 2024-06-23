<?php
// FROM HASH: 8a78b0b4e217474596f0e2bb5c3dfb56
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['reply'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['reply']['User']['username']) ?: $__templater->escape($__vars['reply']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['update'], 'isVisible', array())) ? 'is-deleted' : '') . ' scItemSearchResultRow">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['reply']['User'], 's', false, array(
		'defaultname' => ($__vars['reply']['username'] ?: 'Deleted member'),
	))) . '
		</span>		
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['reply'], ), true) . '">
					' . 'Reply to update \'' . $__templater->escape($__vars['update']['title']) . '\' by \'' . $__templater->escape($__vars['reply']['username']) . '\' on item \'' . $__templater->escape($__vars['update']['Item']['title']) . '\'' . '
				</a>
			</h3>

			<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['reply']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					<li>' . $__templater->func('username_link', array($__vars['reply']['User'], false, array(
		'defaultname' => $__vars['reply']['username'],
	))) . '</li>
					<li>' . 'Showcase update reply' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['reply']['reply_date'], array(
	))) . '</li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);