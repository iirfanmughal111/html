<?php
// FROM HASH: e7b88c4793fcc77b96808a9bba73e814
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['is_review']) {
		$__finalCompiled .= '	
	';
		if ($__vars['content']['is_anonymous']) {
			$__finalCompiled .= '
		' . '' . 'Anonymous' . ' reviewed the item ' . ((((('<a href="' . $__templater->func('link', array('showcase/review', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . '.' . '
	';
		} else {
			$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reviewed the item ' . ((((('<a href="' . $__templater->func('link', array('showcase/review', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . '.' . '
	';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		if ($__vars['content']['is_anonymous']) {
			$__finalCompiled .= '
		' . '' . 'Anonymous' . ' rated the item ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['content']['Item'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . '.' . '	
	';
		} else {
			$__finalCompiled .= '		
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' rated the item ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['content']['Item'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . '.' . '
	';
		}
		$__finalCompiled .= '		
';
	}
	return $__finalCompiled;
}
);