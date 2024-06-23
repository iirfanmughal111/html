<?php
// FROM HASH: 44e98b6ae104432c58c7560c82d43788
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	';
	if ($__vars['content']['Product']['is_paid']) {
		$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => 'Anonymous', ), ), true) . ' purchased the product ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['content']['Product'], ), true)) . '">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], ), true)) . $__templater->escape($__vars['content']['Product']['full_title'])) . '</a>') . '.' . '
	';
	} else {
		$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => 'Anonymous', ), ), true) . ' obtained the product ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['content']['Product'], ), true)) . '">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], ), true)) . $__templater->escape($__vars['content']['Product']['full_title'])) . '</a>') . '.' . '
	';
	}
	$__finalCompiled .= '
</div>

<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['content']['Product']['description'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);