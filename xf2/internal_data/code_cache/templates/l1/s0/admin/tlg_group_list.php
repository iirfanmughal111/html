<?php
// FROM HASH: 1f84796fb9d857b421fd31cb6ce5578e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Groups');
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formSelectRow(array(
		'name' => 'privacy',
		'value' => ($__vars['pageNavParams']['privacy'] ?: ''),
	), array(array(
		'value' => '',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => 'public',
		'label' => 'Public Group',
		'_type' => 'option',
	),
	array(
		'value' => 'closed',
		'label' => 'Closed Group',
		'_type' => 'option',
	),
	array(
		'value' => 'secret',
		'label' => 'Secret Group',
		'_type' => 'option',
	)), array(
		'label' => 'Privacy',
	)) . '

            ' . $__templater->formSelectRow(array(
		'name' => 'group_state',
		'value' => ($__vars['pageNavParams']['group_state'] ?: ''),
	), array(array(
		'value' => '',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => 'visible',
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'label' => 'Moderated',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted',
		'label' => 'Deleted',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'search',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/list', ), false),
		'method' => 'GET',
		'class' => 'block',
	)) . '

';
	if ($__vars['total'] > 0) {
		$__finalCompiled .= '
    <div class="block">
        <div class="block-container">
            <div class="block-body">
                ';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['groups'])) {
			foreach ($__vars['groups'] AS $__vars['group']) {
				$__compilerTemp1 .= '
                        ' . $__templater->dataRow(array(
				), array(array(
					'label' => $__templater->escape($__vars['group']['name']),
					'hint' => $__templater->escape($__vars['group']['privacy']),
					'explain' => $__templater->escape($__templater->method($__vars['controller'], 'getEntityExplain', array($__vars['group'], ))),
					'href' => $__templater->func('link_type', array('public', 'groups', $__vars['group'], ), false),
					'_type' => 'main',
					'html' => '',
				),
				array(
					'href' => $__templater->func('link', array($__vars['linkPrefix'] . '/delete', $__vars['group'], ), false),
					'_type' => 'delete',
					'html' => '',
				))) . '
                    ';
			}
		}
		$__finalCompiled .= $__templater->dataList('
                    ' . $__compilerTemp1 . '
                ', array(
		)) . '
            </div>

            <div class="block-footer">
                <span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['total'], ), true) . '</span>
            </div>
        </div>

        <div class="block-outer block-outer--after">
            ' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => ($__vars['linkPrefix'] . '/list'),
			'params' => $__vars['pageNavParams'],
			'perPage' => $__vars['perPage'],
		))) . '
        </div>
    </div>
';
	} else {
		$__finalCompiled .= '
    <div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
);