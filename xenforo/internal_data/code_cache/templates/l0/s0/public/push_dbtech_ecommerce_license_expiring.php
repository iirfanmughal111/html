<?php
// FROM HASH: 9f6b83c499ef8c425c00c83d1a2d45ec
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your license ' . (($__templater->escape($__vars['content']['Product']['full_title']) . ' - ') . $__templater->escape($__vars['content']['license_key'])) . ' is expiring soon.' . '
' . 'Expiry date' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['content']['expiry_date'], array(
	))) . '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce/licenses/license', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);