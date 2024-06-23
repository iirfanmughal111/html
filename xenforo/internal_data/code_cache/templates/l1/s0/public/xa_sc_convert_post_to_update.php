<?php
// FROM HASH: 8642652f72e44d7372496effb2bb6c99
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Convert to update');
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
				' . '<span style="color:red"> <b>Warning!</b> Performing this action will permanently and irreversibly modify the post and all of its contents (attachments) upon successful conversion to an item update!</span>' . '
			', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['update']['title_'],
		'maxlength' => $__templater->func('max_length', array($__vars['update'], 'title', ), false),
	), array(
		'label' => 'Update title',
		'hint' => 'Required',
		'explain' => 'The update title should be a summarised view of the entire update with 100 characters or less. ',
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
				' . 'Are you sure you want to convert this post to an item update?' . '
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
		'action' => $__templater->func('link', array('posts/convert-post-to-sc-update', $__vars['post'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);