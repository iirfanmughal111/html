<?php
// FROM HASH: 863f9f637ce6ffacd6ba277b0c17548a
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
	if ($__templater->isTraversable($__vars['entry']['forum_list'])) {
		foreach ($__vars['entry']['forum_list'] AS $__vars['id'] => $__vars['forumName']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['id'],
				'label' => $__templater->escape($__vars['forumName']),
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