<?php
// FROM HASH: 8a38a8fbb80adaad4ed4aee8729da6cd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content'], 'plain', ), true) . $__templater->escape($__vars['content']['title'])) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['content'], ), true) . '</push:url>
<push:tag>dbtech_ecommerce_product_' . $__templater->escape($__vars['content']['product_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);