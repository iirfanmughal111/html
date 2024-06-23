<?php
// FROM HASH: 0c2232caa45e07cb7f1ce2700ff34f86
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['success']) {
		$__finalCompiled .= '
	<div class="blockMessage blockMessage--success blockMessage--iconic">' . 'The batch update was completed successfully.' . '</div>
';
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->includeTemplate($__vars['criteriaTemplate'], $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'search',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/confirm', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);