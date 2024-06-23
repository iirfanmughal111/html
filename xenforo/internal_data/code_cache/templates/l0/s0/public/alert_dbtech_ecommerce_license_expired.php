<?php
// FROM HASH: 5334caa7f83c99a4ec505b3b2161922e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your license ' . (((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Product']['full_title'])) . ' - ') . $__templater->escape($__vars['content']['license_key'])) . '</a>') . ' has expired.';
	return $__finalCompiled;
}
);