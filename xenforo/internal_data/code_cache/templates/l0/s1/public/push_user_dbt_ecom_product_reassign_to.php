<?php
// FROM HASH: 1872ae1c3d527f4aa32103504719af8e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'The product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], 'plain', ), true) . $__templater->escape($__vars['extra']['title'])) . ' was reassigned to you.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('base_url', array($__vars['extra']['link'], 'canonical', ), true) . '</push:url>';
	return $__finalCompiled;
}
);