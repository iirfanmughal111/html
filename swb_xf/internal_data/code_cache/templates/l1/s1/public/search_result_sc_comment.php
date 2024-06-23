<?php
// FROM HASH: eb8a5023a0a9cd591ac8ca6a009d5aba
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['comment'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['comment']['User']['username']) ?: $__templater->escape($__vars['comment']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['comment'], 'isVisible', array())) ? 'is-deleted' : '') . ' scItemSearchResultRow">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['comment']['User'], 's', false, array(
		'defaultname' => ($__vars['comment']['username'] ?: 'Deleted member'),
	))) . '
		</span>		
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('showcase/comments', $__vars['comment'], ), true) . '">
					' . 'Comment by \'' . $__templater->escape($__vars['comment']['username']) . '\' in item \'' . $__templater->escape($__vars['comment']['Item']['title']) . '\'' . '
				</a>
			</h3>

			<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['comment']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'sc_comment') AND $__templater->method($__vars['comment'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['comment']['comment_id'],
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['comment']['User'], false, array(
		'defaultname' => $__vars['comment']['username'],
	))) . '</li>
					<li>' . 'Showcase comment' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['comment']['comment_date'], array(
	))) . '</li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);