<?php
// FROM HASH: 03c2f50b8a116173a7552cc3e48d28a4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['item'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['item']['User']['username']) ?: $__templater->escape($__vars['item']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['item'], 'isVisible', array())) ? 'is-deleted' : '') . ' scItemSearchResultRow">
		<span class="contentRow-figure">
			';
	if ($__vars['item']['CoverImage']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
					' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '
				</a>				
			';
	} else if ($__vars['item']['Category']['content_image_url']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
					' . $__templater->func('sc_category_icon', array($__vars['item'], ), true) . '
				</a>					
			';
	} else {
		$__finalCompiled .= '
				' . $__templater->func('avatar', array($__vars['item']['User'], 's', false, array(
			'defaultname' => ($__vars['item']['username'] ?: 'Deleted member'),
		))) . '
			';
	}
	$__finalCompiled .= '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true) . $__templater->func('highlight', array($__vars['item']['title'], $__vars['options']['term'], ), true) . '</a>
			</h3>

			<div class="contentRow-snippet">
				' . $__templater->func('snippet', array($__vars['item']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '
			</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'sc_item') AND $__templater->method($__vars['item'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['item']['item_id'],
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
		'defaultname' => $__vars['item']['username'],
	))) . '</li>
					<li>' . 'Showcase item' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
	))) . '</li>
					';
	if ($__vars['xf']['options']['enableTagging'] AND $__vars['item']['tags']) {
		$__finalCompiled .= '
						<li>
							' . $__templater->callMacro('tag_macros', 'simple_list', array(
			'tags' => $__vars['item']['tags'],
			'containerClass' => 'contentRow-minor',
			'highlightTerm' => ($__vars['options']['tag'] ?: $__vars['options']['term']),
		), $__vars) . '
						</li>
					';
	}
	$__finalCompiled .= '					
					';
	if ($__vars['item']['comment_count']) {
		$__finalCompiled .= '<li>' . 'Comments' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['item']['comment_count'], array(array('number_short', array()),), true) . '</li>';
	}
	$__finalCompiled .= '
					';
	if ($__vars['item']['review_count']) {
		$__finalCompiled .= '<li>' . 'Reviews' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['item']['review_count'], array(array('number_short', array()),), true) . '</li>';
	}
	$__finalCompiled .= '
					<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a></li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);