<?php
// FROM HASH: c8dad56e82ecfe29808ef9d385167dec
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your update to product ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/release', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], ), true)) . $__templater->escape($__vars['content']['Product']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['extra']['reaction_id'], ), false), array(array('preescaped', array()),), true) . '.';
	return $__finalCompiled;
}
);