<?php
// FROM HASH: 371e24ecf5c58212388053fa8025c72a
return array(
'macros' => array('link' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'confirmUrl' => '!',
		'editText' => 'Edit bookmark',
		'addText' => 'Add bookmark',
		'showText' => true,
		'class' => 'actionBar-action actionBar-action--bookmarkLink',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
		<a href="' . $__templater->escape($__vars['confirmUrl']) . '" class="bookmarkLink ' . $__templater->escape($__vars['class']) . ' ' . ($__templater->method($__vars['content'], 'isBookmarked', array()) ? 'is-bookmarked' : '') . '"
			title="' . ($__vars['showText'] ? '' : $__templater->filter('Bookmark', array(array('for_attr', array()),), true)) . '"
			data-xf-click="bookmark-click"
			data-label=".js-bookmarkText"
			data-sk-bookmarked="addClass:is-bookmarked, ' . $__templater->filter($__vars['editText'], array(array('for_attr', array()),), true) . '"
			data-sk-bookmarkremoved="removeClass:is-bookmarked, ' . $__templater->filter($__vars['addText'], array(array('for_attr', array()),), true) . '">';
	$__compilerTemp1 = '';
	if ($__vars['auction']) {
		$__compilerTemp1 .= $__templater->escape($__vars['editText']);
	} else {
		$__compilerTemp1 .= $__templater->escape($__vars['addText']);
	}
	$__finalCompiled .= $__templater->func('trim', array('
			<span class="js-bookmarkText ' . ($__vars['showText'] ? '' : 'u-srOnly') . '">' . $__compilerTemp1 . '</span>
		'), false) . '</a>

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