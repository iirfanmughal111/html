<?php
// FROM HASH: 03432073afb39494d52fb7eb7e885925
return array(
'macros' => array('option_form_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '',
		'options' => '!',
		'containerBeforeHtml' => '',
		'headers' => array(),
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '


    ';
	if (!$__templater->test($__vars['options'], 'empty', array())) {
		$__finalCompiled .= '
        ';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['headers'])) {
			foreach ($__vars['headers'] AS $__vars['key'] => $__vars['header']) {
				$__compilerTemp1 .= '
                            <a class="tabs-tab' . ($__vars['header']['active'] ? ' is-active' : '') . '" role="tab" tabindex="0"
                               aria-controls="' . $__templater->escape($__vars['key']) . '">' . $__templater->escape($__vars['header']['label']) . '</a>
                        ';
			}
		}
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['headers'])) {
			foreach ($__vars['headers'] AS $__vars['key'] => $__vars['header']) {
				$__compilerTemp2 .= '
                        <li' . ($__vars['header']['active'] ? ' class="is-active"' : '') . ' role="tabpanel" id="' . $__templater->escape($__vars['key']) . '">
                            ' . $__templater->callMacro(null, 'option_form_rows', array(
					'group' => $__vars['group'],
					'options' => $__vars['options'],
					'minDisplayOrder' => $__vars['header']['minDisplayOrder'],
					'maxDisplayOrder' => $__vars['header']['maxDisplayOrder'],
				), $__vars) . '
                        </li>
                    ';
			}
		}
		$__finalCompiled .= $__templater->form('
            <div class="block-container">
                ' . $__templater->filter($__vars['containerBeforeHtml'], array(array('raw', array()),), true) . '
                <h2 class="block-tabHeader tabs hScroller" data-xf-init="h-scroller tabs" role="tablist">
                    <span class="hScroller-scroll">
                        ' . $__compilerTemp1 . '
                    </span>
                </h2>

                <ul class="tabPanes">
                    ' . $__compilerTemp2 . '
                </ul>
                ' . $__templater->formSubmitRow(array(
			'sticky' => 'true',
			'icon' => 'save',
		), array(
		)) . '
            </div>
        ', array(
			'action' => $__templater->func('link', array('options/update', ), false),
			'ajax' => 'true',
			'class' => 'block',
		)) . '
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'option_form_rows' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'options' => '!',
		'minDisplayOrder' => 0,
		'maxDisplayOrder' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__vars['hundred'] = '0';
	$__finalCompiled .= '
    <div class="block-body">
        ';
	if ($__templater->isTraversable($__vars['options'])) {
		foreach ($__vars['options'] AS $__vars['option']) {
			$__finalCompiled .= '
            ';
			if (($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] < $__vars['maxDisplayOrder']) AND ($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] >= $__vars['minDisplayOrder'])) {
				$__finalCompiled .= '
                ';
				if ($__vars['group']) {
					$__finalCompiled .= '
                    ';
					$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
					$__finalCompiled .= '
                    ';
					if (($__vars['curHundred'] > $__vars['hundred'])) {
						$__finalCompiled .= '
                        ';
						$__vars['hundred'] = $__vars['curHundred'];
						$__finalCompiled .= '
                        <hr class="formRowSep" />
                    ';
					}
					$__finalCompiled .= '
                ';
				}
				$__finalCompiled .= '

                ' . $__templater->callMacro('option_macros', 'option_row', array(
					'group' => $__vars['group'],
					'option' => $__vars['option'],
				), $__vars) . '
            ';
			}
			$__finalCompiled .= '
        ';
		}
	}
	$__finalCompiled .= '
    </div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);