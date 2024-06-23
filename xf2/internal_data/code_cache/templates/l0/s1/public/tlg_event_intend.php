<?php
// FROM HASH: 6f30e8053049e41c5eb9d14022319463
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Intention');
	$__finalCompiled .= '

';
	if (!$__vars['quickIntend']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = $__templater->preEscaped('events');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['intends'])) {
		foreach ($__vars['intends'] AS $__vars['intend'] => $__vars['title']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['intend'],
				'label' => $__templater->escape($__vars['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formRadioRow(array(
		'name' => 'intend',
		'value' => $__vars['guest']['intend'],
	), $__compilerTemp2, array(
		'label' => 'Intend to be' . $__vars['xf']['language']['ellipsis'],
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('group-events/intend', $__vars['event'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);