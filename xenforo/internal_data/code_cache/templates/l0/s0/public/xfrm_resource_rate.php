<?php
// FROM HASH: d10a9c9b58025b5a800d3a9db956d1cb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Rate this resource');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['existingRating']) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('
					' . 'You have already rated this version. Re-rating it will remove your existing rating or review.' . '
				', array(
			'rowtype' => 'confirm',
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['xfrmMinimumReviewLength']) {
		$__compilerTemp2 .= '
						<span id="js-resourceReviewLength">' . 'Your review must be at least ' . $__templater->escape($__vars['xf']['options']['xfrmMinimumReviewLength']) . ' characters.' . '</span>
					';
	}
	$__compilerTemp3 = '';
	if ($__vars['xf']['options']['xfrmReviewRequired']) {
		$__compilerTemp3 .= 'Required';
	}
	$__compilerTemp4 = '';
	if ($__vars['xf']['options']['xfrmAllowAnonReview']) {
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
		'type' => 'resourceReviews',
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
		'data-min-length' => $__vars['xf']['options']['xfrmMinimumReviewLength'],
		'data-allow-empty' => ($__vars['xf']['options']['xfrmReviewRequired'] ? 'false' : 'true'),
		'data-toggle-target' => '#js-resourceReviewLength',
		'maxlength' => $__vars['xf']['options']['messageMaxLength'],
	), array(
		'label' => 'Review',
		'explain' => '
					' . 'Explain why you\'re giving this rating. Reviews which are not constructive may be removed without notice.' . '
					' . $__compilerTemp2 . '
				',
		'hint' => $__compilerTemp3,
	)) . '

			' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'resourceReviews',
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
		'action' => $__templater->func('link', array('resources/rate', $__vars['resource'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'novalidate' => ($__vars['xf']['options']['xfrmReviewRequired'] ? false : 'novalidate'),
	));
	return $__finalCompiled;
}
);