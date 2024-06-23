<?php
// FROM HASH: f7eb7c2206ff9438b5aa138fd5e97343
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Latest reviews');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

<div class="block">
	<div class="block-outer">' . $__templater->func('trim', array('

		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/latest-reviews',
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

	'), false) . '</div>

	<div class="block-container">
		<div class="block-body">
			';
	if (!$__templater->test($__vars['reviews'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['reviews'])) {
			foreach ($__vars['reviews'] AS $__vars['review']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('dbtech_ecommerce_product_review_macros', 'review', array(
					'review' => $__vars['review'],
					'product' => $__vars['review']['Product'],
					'showProduct' => true,
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No reviews have been left recently.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/latest-reviews',
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);