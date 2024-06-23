<?php
// FROM HASH: 3685ab4f6166f9d07227a9225baa1332
return array(
'macros' => array('log_row' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'log' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block-row">
        <div class="contentRow contentRow--alignMiddle">
            <div class="contentRow-figure">
                ' . $__templater->func('avatar', array($__vars['log']['User'], 'xs', false, array(
	))) . '
            </div>
            <div class="contentRow-main">
                <div class="contentRow-extra contentRow-extra--large">' . $__templater->filter($__vars['log']['total'], array(array('number', array()),), true) . '</div>
                <h3 class="contentRow-title">' . $__templater->func('username_link', array($__vars['log']['User'], true, array(
	))) . '</h3>
                <div class="contentRow-minor contentRow-minor--hideLinks">
                    <ul class="listInline listInline--bullet">
                        <li>
                            ' . $__templater->fontAwesome('fa-download', array(
	)) . '
                            ' . $__templater->func('date_dynamic', array($__vars['log']['download_date'], array(
	))) . '
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['resource']['title']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('resources');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title">
        <h2 class="p-title-value">
            ' . 'Users who download this resource' . $__vars['xf']['language']['label_separator'] . '
            <a href="' . $__templater->func('link', array('group-resources', $__vars['resource'], ), true) . '">' . $__templater->escape($__vars['resource']['title']) . '</a>
        </h2>
    </div>
</div>

<div class="block">
    <div class="block-container">
        <div class="block-body">
            ';
	if ($__vars['total'] > 0) {
		$__finalCompiled .= '
                ';
		if ($__templater->isTraversable($__vars['entities'])) {
			foreach ($__vars['entities'] AS $__vars['log']) {
				$__finalCompiled .= '
                    ' . $__templater->callMacro(null, 'log_row', array(
					'log' => $__vars['log'],
				), $__vars) . '
                ';
			}
		}
		$__finalCompiled .= '
            ';
	} else {
		$__finalCompiled .= '
                <div class="blockMessage">' . 'There are no users download this resource.' . '</div>
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>

    <div class="block-outer block-outer--after">
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'group-resources/logs',
		'data' => $__vars['resource'],
		'perPage' => $__vars['perPage'],
	))) . '
    </div>
</div>

';
	return $__finalCompiled;
}
);