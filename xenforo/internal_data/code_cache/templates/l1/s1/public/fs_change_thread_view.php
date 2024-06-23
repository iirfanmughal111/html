<?php
// FROM HASH: 5ab50d88d2ab42fb49cf0c1fe32bfd58
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['thread']['is_view_change']) {
		$__finalCompiled .= '
	<a href="' . $__templater->func('link', array('threads/article-view', $__vars['thread'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'To Article View' . '</a>
';
	} else {
		$__finalCompiled .= '
  <a href="' . $__templater->func('link', array('threads/normal-view', $__vars['thread'], ), true) . '" data-xf-click="overlay"  class="menu-linkRow">' . 'To Normal View' . '</a>
';
	}
	return $__finalCompiled;
}
);