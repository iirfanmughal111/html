<?php
// FROM HASH: ec8c02316b2e3e07ee9ac143b9776a1e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->filter($__vars['phrase'], array(array('raw', array()),), true) . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-credits/currency', $__vars['content']['Currency'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);