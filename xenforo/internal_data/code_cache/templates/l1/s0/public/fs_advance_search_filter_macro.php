<?php
// FROM HASH: 1e6472ed2f34c8a120f8679dee5d0a4a
return array(
'macros' => array('filters' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'forum' => '!',
		'filters' => '!',
		'starterFilter' => null,
		'threadTypeFilter' => null,
	); },
'extensions' => array('start' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	
	return $__finalCompiled;
},
'prefix_id' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
		';
	if ($__vars['filters']['prefix_id']) {
		$__finalCompiled .= '
			<li><a href="' . $__templater->func('link', array('search/explore', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('prefix_id', null, )),), false), ), true) . '"
				class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
				<span class="filterBar-filterToggle-label">' . 'Prefix' . $__vars['xf']['language']['label_separator'] . '</span>
				' . $__templater->func('prefix_title', array('thread', $__vars['filters']['prefix_id'], ), true) . '</a></li>
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
},
'starter_id' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	
		';
	if ($__vars['filters']['starter_id'] AND $__vars['starterFilter']) {
		$__finalCompiled .= '
			<li><a href="' . $__templater->func('link', array('search/explore', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('starter_id', null, )),), false), ), true) . '"
				class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
				<span class="filterBar-filterToggle-label">' . 'Started by' . $__vars['xf']['language']['label_separator'] . '</span>
				 ' . $__templater->escape($__vars['starterFilter']['username']) . '</a></li>
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
},
'thread_type' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
		';
	if ($__vars['filters']['thread_type']) {
		$__finalCompiled .= '
			<li><a href="' . $__templater->func('link', array('search/explore', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('thread_type', null, )),), false), ), true) . '"
				class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
				<span class="filterBar-filterToggle-label">' . 'Thread type' . $__vars['xf']['language']['label_separator'] . '</span>
				' . $__templater->func('phrase_dynamic', array('thread_type.' . $__vars['filters']['thread_type'], ), true) . '</a></li>
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
},
'last_days' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
		';
	if ($__vars['filters']['last_days'] AND $__vars['dateLimits'][$__vars['filters']['last_days']]) {
		$__finalCompiled .= '
			<li><a href="' . $__templater->func('link', array('search/explore', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('last_days', null, )),), false), ), true) . '"
				class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
				<span class="filterBar-filterToggle-label">' . 'Last updated' . $__vars['xf']['language']['label_separator'] . '</span>
				' . $__templater->escape($__vars['dateLimits'][$__vars['filters']['last_days']]) . '</a></li>
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
},
'order' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
		';
	if ($__vars['filters']['order']) {
		$__finalCompiled .= '
			<li><a href="' . $__templater->func('link', array('search/explore', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
				class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
				<span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
				' . $__templater->func('phrase_dynamic', array('forum_sort.' . $__vars['filters']['order'], ), true) . '
				' . $__templater->fontAwesome((($__vars['filters']['direction'] == 'asc') ? 'fa-angle-up' : 'fa-angle-down'), array(
		)) . '
				<span class="u-srOnly">';
		if ($__vars['filters']['direction'] == 'asc') {
			$__finalCompiled .= 'Ascending';
		} else {
			$__finalCompiled .= 'Descending';
		}
		$__finalCompiled .= '</span>
			</a></li>
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
},
'end' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['dateLimits'] = array('-1' => 'Any time', '7' => '' . '7' . ' days', '14' => '' . '14' . ' days', '30' => '' . '30' . ' days', '60' => '' . '2' . ' months', '90' => '' . '3' . ' months', '182' => '' . '6' . ' months', '365' => '1 year', );
	$__finalCompiled .= '
	' . $__templater->renderExtension('start', $__vars, $__extensions) . '

	' . $__templater->renderExtension('prefix_id', $__vars, $__extensions) . '

	' . $__templater->renderExtension('starter_id', $__vars, $__extensions) . '
	' . $__templater->renderExtension('thread_type', $__vars, $__extensions) . '

	' . $__templater->renderExtension('last_days', $__vars, $__extensions) . '

	' . $__templater->renderExtension('order', $__vars, $__extensions) . '

	' . $__templater->renderExtension('end', $__vars, $__extensions) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);