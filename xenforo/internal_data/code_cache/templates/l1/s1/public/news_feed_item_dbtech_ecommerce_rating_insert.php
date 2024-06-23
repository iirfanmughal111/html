<?php
// FROM HASH: 95612792d38263966ca00c96eafaf0fb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-title">
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => 'Anonymous', ), ), true) . ' reviewed the product ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/review', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['content']['Product'], ), true)) . $__templater->escape($__vars['content']['Product']['full_title'])) . '</a>') . '.' . '
</div>

<div class="contentRow-snippet">
	' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['content']['rating'],
		'class' => 'ratingStars--smaller',
	), $__vars) . '

	' . $__templater->func('snippet', array($__vars['content']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], ), true) . '
</div>


<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['newsFeed']['event_date'], array(
	))) . '</div>';
	return $__finalCompiled;
}
);