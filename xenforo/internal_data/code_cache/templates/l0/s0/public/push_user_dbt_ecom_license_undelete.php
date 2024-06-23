<?php
// FROM HASH: 47dbfe6ffbe41d44101e5257444c3946
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your license ' . (((($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], 'plain', ), true) . $__templater->escape($__vars['extra']['title'])) . ' [') . $__templater->escape($__vars['extra']['license_key'])) . ']') . ' was undeleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('base_url', array($__vars['extra']['link'], 'canonical', ), true) . '</push:url>';
	return $__finalCompiled;
}
);