<?php
// FROM HASH: 662b3b93545aa052ea8a45a24f7563b9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
        ';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['optionTitle']));
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['entry']['group_list'])) {
		foreach ($__vars['entry']['group_list'] AS $__vars['id'] => $__vars['groupName']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['id'],
				'label' => $__templater->escape($__vars['groupName']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => $__vars['fieldPrefix'] . '[' . $__vars['optionId'] . ']',
		'value' => $__templater->filter($__vars['entry']['choices'], array(array('raw', array()),), false),
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 350px',
	), $__compilerTemp1, array(
		'explain' => $__templater->escape($__vars['optionExplain']),
		'label' => $__templater->escape($__vars['optionTitle']),
	)) . $__templater->formHiddenVal('options_listed[]', $__vars['optionId'], array(
	));
	return $__finalCompiled;
}
);