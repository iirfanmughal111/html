<?php
// FROM HASH: 475771aa4d82b097da59c186a9ad5324
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Convert to review');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['thread'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['post'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(
			'selected' => true,
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . '<span style="color:red"> <b>Warning!</b> Performing this action will permanently and irreversibly modify the post and all of its contents (attachments) upon successful conversion to a review!</span>' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
			
			' . $__templater->callMacro('rating_macros', 'rating', array(), $__vars) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['review']['title_'],
		'maxlength' => $__templater->func('max_length', array($__vars['review'], 'title', ), false),
	), array(
		'label' => 'Review title',
		'hint' => ($__vars['xf']['options']['xaScRequireReviewTitle'] ? 'Required' : 'Optional'),
		'explain' => ($__vars['xf']['options']['xaScRequireReviewTitle'] ? 'The review title should be a summarised view of the entire review with 100 characters or less.  
<br>
<b>A Review title is required when submitting a review.</b>' : 'The <b>optional</b> review title should be a summarized view of the entire review with 100 characters or less. '),
	)) . '			
			
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'notify_watchers',
		'value' => '1',
		'selected' => true,
		'label' => 'Notify members watching the destination item.',
		'_type' => 'option',
	)), array(
	)) . '
			
			' . $__compilerTemp1 . '
			
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to convert this post to a review?' . '
			', array(
		'rowtype' => 'confirm',
	)) . '			
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'confirm',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('posts/convert-post-to-sc-review', $__vars['post'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);