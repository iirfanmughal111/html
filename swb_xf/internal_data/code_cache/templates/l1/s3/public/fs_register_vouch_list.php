<?php
// FROM HASH: 2a437d0bacd1c6630dbc62f690610dc0
return array(
'macros' => array('fs_register_vouch_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'lists' => $__vars['lists'],
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Avatar' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'username' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Date' . ' ',
	))) . '
	';
	if ($__templater->isTraversable($__vars['lists'])) {
		foreach ($__vars['lists'] AS $__vars['list']) {
			$__finalCompiled .= '
		' . $__templater->dataRow(array(
			), array(array(
				'_type' => 'cell',
				'html' => $__templater->func('avatar', array($__vars['list']['UserTo'], 's', false, array(
				'defaultname' => $__vars['listing']['UserTo']['username'],
			))),
			),
			array(
				'_type' => 'cell',
				'html' => '  ' . $__templater->escape($__vars['list']['UserTo']['username']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->func('date_dynamic', array($__vars['list']['created_at'], array(
			))) . ' ',
			))) . '
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('fs_register_vouch_list');
	$__finalCompiled .= '

		';
	$__compilerTemp1 = '';
	if ($__templater->func('count', array($__vars['lists'], ), false)) {
		$__compilerTemp1 .= '
					<div class="block-container block-body">
						' . $__templater->dataList('
							' . $__templater->callMacro(null, 'fs_register_vouch_table_list', array(
			'lists' => $__vars['lists'],
		), $__vars) . '
						', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
					</div>

					';
	} else {
		$__compilerTemp1 .= '
						' . 'No items have been created yet.' . '
				';
	}
	$__finalCompiled .= $__templater->form('
			<div class="">
				' . $__compilerTemp1 . '
			</div>
		', array(
		'action' => $__templater->func('link', array($__vars['prefix'] . '/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '

';
	return $__finalCompiled;
}
);