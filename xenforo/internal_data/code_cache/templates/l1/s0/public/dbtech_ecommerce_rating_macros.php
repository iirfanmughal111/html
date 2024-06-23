<?php
// FROM HASH: 2477cfa18bd5ad0d6e14e0595a9b0100
return array(
'macros' => array('stars_circle' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'rating' => '!',
		'count' => null,
		'text' => null,
		'rowClass' => '',
		'starsClass' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('dbtech_ecommerce_rating_circle.less');
	$__finalCompiled .= '

	<div class="rating-circle rating-' . ($__templater->filter($__vars['rating'], array(array('number', array(1, )),), false) * 20) . ' ' . (($__vars['rating'] >= 2.5) ? ' overHalf' : '') . '">
		<div class="ratingCircleRow">
			<div class="ratingCircleRow-inner">
				<span class="ratingPercent">' . ($__templater->filter($__vars['rating'], array(array('number', array(2, )),), false) * 20) . '%</span>
				' . $__templater->callMacro('rating_macros', 'stars_text', array(
		'rating' => $__vars['rating'],
		'count' => $__vars['count'],
		'rowClass' => 'ratingStarsRow--textBlock',
	), $__vars) . '
			</div>
		</div>
		<div class="leftCover">
			<div class="initialBar"></div>
			<div class="valueBar"></div>
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);