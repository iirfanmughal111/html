<?php
// FROM HASH: 92f3b3f2ccfba091535075063c40a89e
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
		'html' => ' ' . 'User group' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'link_delay_time' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'link_html' . ' ',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => 'fs_link_edit',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => 'fs_Link_delete',
	))) . '
	';
	if ($__templater->isTraversable($__vars['lists'])) {
		foreach ($__vars['lists'] AS $__vars['list']) {
			$__finalCompiled .= '
		' . $__templater->dataRow(array(
			), array(array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->func('avatar', array($__vars['list']['UserTo'], 'o', false, array(
				'defaultname' => $__vars['listing']['UserTo']['username'],
			))) . ' ' . $__templater->escape($__vars['list']['UserGroup']['title']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['list']['redirect_time']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => '
				' . (($__vars['list']['link_redirect_html'] != '') ? $__templater->func('snippet', array($__vars['list']['link_redirect_html'], 30, array('stripBbCode' => true, ), ), true) : 'no_link_redirect_html') . '
			',
			),
			array(
				'href' => $__templater->func('link', array('link-proxy/edit', $__vars['list'], ), false),
				'_type' => 'action',
				'html' => 'Edit',
			),
			array(
				'href' => $__templater->func('link', array('link-proxy/delete', $__vars['list'], ), false),
				'overlay' => 'true',
				'_type' => 'delete',
				'html' => '',
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

<div class="block-container">
	<div class="block-body">
		';
	$__compilerTemp1 = '';
	if ($__vars['lists'] != null) {
		$__compilerTemp1 .= '
					<div class="block-body">
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
					<div class="block-body block-row">
						' . 'No items have been created yet.' . '
					</div>
				';
	}
	$__finalCompiled .= $__templater->form('
			<div class="block-container">
				' . $__compilerTemp1 . '
			</div>
		', array(
		'action' => $__templater->func('link', array($__vars['prefix'] . '/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
	</div>

	<div class="block-footer">

		<div class="block-body block-row">
			<dl class="pairs pairs--justified">
				<dt><span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['attendance'], $__vars['total'], ), true) . '</span></dt>
				<dd>
					' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'link-proxy',
		'data' => $__vars['lists'],
		'params' => $__vars['lists'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
				</dd>
			</dl>
		</div>
	</div>
</div>



';
	return $__finalCompiled;
}
);