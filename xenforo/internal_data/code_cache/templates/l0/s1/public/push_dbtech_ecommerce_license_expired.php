<?php
// FROM HASH: bd9b6b4af613c65bd8a7c7db54ce0345
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your license ' . (($__templater->escape($__vars['content']['Product']['full_title']) . ' - ') . $__templater->escape($__vars['content']['license_key'])) . ' has expired.' . '
' . 'Expiry date' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['content']['expiry_date'], array(
	))) . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce/licenses/license', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);