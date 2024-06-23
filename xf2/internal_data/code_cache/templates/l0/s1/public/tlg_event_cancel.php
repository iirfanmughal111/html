<?php
// FROM HASH: 6a04576f63a858d268ec50bd8d02ca05
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Cancel event');
	$__finalCompiled .= '

';
	if ($__vars['enableWrapper']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = $__templater->preEscaped('events');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formInfoRow('
                <p>' . 'Are you sure that you want to cancel the following event' . '</p>
                <a href="' . $__templater->func('link', array('group-events', $__vars['event'], ), true) . '">' . $__templater->escape($__vars['event']['event_name']) . '</a>
            ', array(
		'rowtype' => 'confirm',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('group-events/cancel', $__vars['event'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);