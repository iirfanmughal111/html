<?php
// FROM HASH: 22b5931fc287aecc5c26522e0cde3faa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['newsFeed']['username'], ), ), true) . ' created the product ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content'], ), true)) . $__templater->escape($__vars['content']['full_title'])) . '</a>') . ' in ' . (((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/categories', $__vars['content']['Category'], ), true)) . '">') . $__templater->escape($__vars['content']['Category']['title'])) . '</a>') . '.' . '
</div>

<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['content']['description'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], array('stripQuote' => true, ), ), true) . '</div>
';
	if ($__vars['content']['attach_count']) {
		$__finalCompiled .= '
	' . $__templater->callMacro('news_feed_attached_images', 'attached_images', array(
			'attachments' => $__vars['content']['Attachments'],
			'link' => $__templater->func('link', array('dbtech-ecommerce', $__vars['content'], ), false),
		), $__vars) . '
';
	}
	$__finalCompiled .= '

<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);