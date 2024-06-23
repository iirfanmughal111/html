<?php
// FROM HASH: 44bdc693c69141446881c0cd7fde1866
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['page'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['page']['User']['username']) ?: $__templater->escape($__vars['page']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['page'], 'isVisible', array())) ? 'is-deleted' : '') . ' scItemSearchResultRow">
		<span class="contentRow-figure">
			';
	if ($__vars['page']['cover_image_id']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase/page', $__vars['page'], ), true) . '">
					' . $__templater->func('sc_item_page_thumbnail', array($__vars['page'], $__vars['page']['Item'], ), true) . '
				</a>	
			';
	} else if ($__vars['page']['Item']['cover_image_id']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase/page', $__vars['page'], ), true) . '">
					' . $__templater->func('sc_item_thumbnail', array($__vars['page']['Item'], ), true) . '
				</a>				
			';
	} else if ($__vars['page']['Item']['Category']['content_image_url']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase/page', $__vars['page'], ), true) . '">
					' . $__templater->func('sc_category_icon', array($__vars['page']['Item'], ), true) . '
				</a>				
			';
	} else {
		$__finalCompiled .= '
				' . $__templater->func('avatar', array($__vars['page']['User'], 's', false, array(
			'defaultname' => ($__vars['page']['username'] ?: 'Deleted member'),
		))) . '
			';
	}
	$__finalCompiled .= '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('showcase/page', $__vars['page'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['page']['Item'], ), true) . $__templater->func('highlight', array($__vars['page']['Item']['title'], $__vars['options']['term'], ), true) . ' | ' . $__templater->func('highlight', array($__vars['page']['title'], $__vars['options']['term'], ), true) . '</a>
			</h3>

			<div class="contentRow-snippet">
				' . $__templater->func('snippet', array($__vars['page']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '
			</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'sc_page') AND $__templater->method($__vars['page'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['page']['page_id'],
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['page']['User'], false, array(
		'defaultname' => $__vars['page']['username'],
	))) . '</li>
					<li>' . 'Showcase page' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['page']['create_date'], array(
	))) . '</li>
					';
	if ($__vars['xf']['options']['enableTagging'] AND $__vars['page']['Item']['tags']) {
		$__finalCompiled .= '
						<li>
							' . $__templater->callMacro('tag_macros', 'simple_list', array(
			'tags' => $__vars['page']['Item']['tags'],
			'containerClass' => 'contentRow-minor',
			'highlightTerm' => ($__vars['options']['tag'] ?: $__vars['options']['term']),
		), $__vars) . '
						</li>
					';
	}
	$__finalCompiled .= '					
					<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('showcase/categories', $__vars['page']['Item']['Category'], ), true) . '">' . $__templater->escape($__vars['page']['Item']['Category']['title']) . '</a></li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);