<?php
// FROM HASH: 9c982d5fc53d1597fbfb3056f6726d05
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your update to product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], 'plain', ), true) . $__templater->escape($__vars['content']['Product']['title'])) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce/release', $__vars['content'], ), true) . '</push:url>
<push:tag>dbtech_ecommerce_download_' . $__templater->escape($__vars['content']['download_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);