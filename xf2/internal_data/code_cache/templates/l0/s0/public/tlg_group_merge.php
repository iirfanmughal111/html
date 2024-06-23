<?php
// FROM HASH: 7ddabba067eef05cd4e353fd1e5cde57
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Merge group');
	$__finalCompiled .= '

';
	if ($__vars['hasWrapper']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['selected'] = $__templater->preEscaped('about');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formTextBoxRow(array(
		'name' => 'source_group',
		'ac' => 'single',
		'data-acurl' => $__templater->func('link', array('groups/find', ), false),
	), array(
		'label' => 'Merge with group',
	)) . '

            ' . $__templater->formRadioRow(array(
		'name' => 'alert_type',
		'value' => 'admin',
	), array(array(
		'value' => '',
		'label' => 'Do not send notifications',
		'_type' => 'option',
	),
	array(
		'value' => 'admin',
		'label' => 'Send notifications to admin and moderator of source group only.',
		'_type' => 'option',
	),
	array(
		'value' => 'all',
		'label' => 'Send notifications to all members of source group.',
		'_type' => 'option',
	)), array(
		'label' => 'Options',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('groups/merge', $__vars['group'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);