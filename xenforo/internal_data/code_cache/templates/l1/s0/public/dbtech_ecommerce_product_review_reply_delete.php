<?php
// FROM HASH: d62a23c82a242e364c5306a44d47e6e9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('dbtech_ecommerce_delete_review_response');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['product'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to delete this review response?' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/review/reply-delete', $__vars['review'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);