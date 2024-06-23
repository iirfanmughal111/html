<?php
// FROM HASH: f7d565d1958570fe799ab0cfbc8cedc2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Batch update items');
	$__finalCompiled .= '

';
	if ($__vars['success']) {
		$__finalCompiled .= '
	<div class="blockMessage blockMessage--success blockMessage--iconic">' . 'The batch update was completed successfully.' . '</div>
';
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->includeTemplate('xa_sc_helper_item_search_criteria', $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'search',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('xa-sc/batch-update/confirm', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);