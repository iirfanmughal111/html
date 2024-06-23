<?php
// FROM HASH: aceb936074d276eac7a7aa242d85bee8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Rate this product');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['product'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['existingRating']) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('
					' . 'You have already rated this product. Re-rating it will remove your existing rating or review.' . '
				', array(
			'rowtype' => 'confirm',
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['dbtechEcommerceReviewRequired']) {
		$__compilerTemp2 .= '
						' . 'A review is required.' . '
					';
	}
	$__compilerTemp3 = '';
	if ($__vars['xf']['options']['dbtechEcommerceMinimumReviewLength']) {
		$__compilerTemp3 .= '
						<span id="js-productReviewLength">' . 'Your review must be at least ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceMinimumReviewLength']) . ' characters.' . '</span>
					';
	}
	$__compilerTemp4 = '';
	if ($__vars['xf']['options']['dbtechEcommerceAllowAnonReview']) {
		$__compilerTemp4 .= '
				' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'is_anonymous',
			'label' => 'Submit review anonymously',
			'hint' => 'If selected, only staff will be able to see who wrote this review.',
			'_type' => 'option',
		)), array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '

			' . $__templater->callMacro('rating_macros', 'rating', array(), $__vars) . '

			' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'dbtechEcommerceReviews',
		'set' => $__templater->method($__vars['rating'], 'getCustomFields', array()),
		'group' => 'above_review',
		'editMode' => 'user',
		'onlyInclude' => $__vars['category']['review_field_cache'],
	), $__vars) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'message',
		'rows' => '2',
		'autosize' => 'true',
		'data-xf-init' => 'min-length',
		'data-min-length' => $__vars['xf']['options']['dbtechEcommerceMinimumReviewLength'],
		'data-allow-empty' => ($__vars['xf']['options']['dbtechEcommerceReviewRequired'] ? 'false' : 'true'),
		'data-toggle-target' => '#js-productReviewLength',
		'maxlength' => $__vars['xf']['options']['messageMaxLength'],
	), array(
		'label' => 'Review',
		'explain' => '
					' . 'Explain why you\'re giving this rating. Reviews which are not constructive may be removed without notice.' . '
					' . $__compilerTemp2 . '
					' . $__compilerTemp3 . '
				',
	)) . '

			' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'dbtechEcommerceReviews',
		'set' => $__templater->method($__vars['rating'], 'getCustomFields', array()),
		'group' => 'below_review',
		'editMode' => 'user',
		'onlyInclude' => $__vars['category']['review_field_cache'],
	), $__vars) . '
			
			' . $__compilerTemp4 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Submit rating',
		'icon' => 'rate',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/rate', $__vars['product'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);