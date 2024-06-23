<?php
// FROM HASH: 4e6a2fb3c640eb4aae3846e16b4666b7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['groups'], 'empty', array())) {
		$__finalCompiled .= '
    ';
		if ($__vars['style'] == 'simple') {
			$__finalCompiled .= '
        ';
			$__templater->includeCss('tls_group_list.less');
			$__finalCompiled .= '
        <div class="block widget-groups"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
            <div class="block-container">
                ';
			if ($__vars['title']) {
				$__finalCompiled .= '<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>';
			}
			$__finalCompiled .= '
                <ul class="block-body">
                    ';
			if ($__templater->isTraversable($__vars['groups'])) {
				foreach ($__vars['groups'] AS $__vars['group']) {
					$__finalCompiled .= '
                        <li class="block-row">
                            ' . $__templater->callMacro('tlg_group_list_macros', 'group_simple', array(
						'group' => $__vars['group'],
					), $__vars) . '
                        </li>
                    ';
				}
			}
			$__finalCompiled .= '
                </ul>
            </div>
        </div>
    ';
		} else if ($__vars['style'] == 'full') {
			$__finalCompiled .= '
        <div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
            <div class="block-container">
                ';
			if ($__vars['title']) {
				$__finalCompiled .= '<h3 class="block-header">' . $__templater->escape($__vars['title']) . '</h3>';
			}
			$__finalCompiled .= '

                <div class="block-body">
                    ' . $__templater->callMacro('tlg_group_list_macros', 'group_list', array(
				'columnsPerRow' => $__vars['itemsPerRow'],
				'showMembers' => $__vars['showMembers'],
				'showSettings' => false,
				'groups' => $__vars['groups'],
			), $__vars) . '
                </div>
            </div>
        </div>
    ';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);