<?php
// FROM HASH: 8e43d2c4d34c44793dcb49f619302f11
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Rate');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['content'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['content'], 'hasRated', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('
					<div class="blockMessage blockMessage--important"><strong>' . 'Note' . $__vars['xf']['language']['label_separator'] . '</strong> ' . 'This rating will replace your previous rating.' . '</div>
				', array(
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['content'], 'canAddComment', array())) {
		$__compilerTemp2 .= '
				' . $__templater->formEditorRow(array(
			'name' => 'message',
			'data-min-height' => '100',
		), array(
			'label' => 'Leave a comment',
			'hint' => ($__vars['xf']['options']['xfmgRequireComment'] ? 'Required' : ''),
			'explain' => 'If you wish to leave a comment with this rating, enter a message above. The rating and comment will be displayed publicly.',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '

			' . $__templater->callMacro('rating_macros', 'rating', array(), $__vars) . '

			' . $__compilerTemp2 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Submit rating',
		'icon' => 'rate',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/rate', $__vars['content'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);