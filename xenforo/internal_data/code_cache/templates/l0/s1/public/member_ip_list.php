<?php
// FROM HASH: d2e195eec01090d28633957771669c1a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('IP addresses logged for ' . $__templater->escape($__vars['user']['username']) . '');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->dataList('
				' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => 'IP',
	),
	array(
		'_type' => 'cell',
		'html' => 'Total',
	),
	array(
		'_type' => 'cell',
		'html' => 'Earliest',
	),
	array(
		'_type' => 'cell',
		'html' => 'Latest',
	),
	array(
		'_type' => 'cell',
		'html' => '&nbsp;',
	))) . '
				' . $__templater->includeTemplate('member_user_ip_list_replace', $__vars) . '
			', array(
		'data-xf-init' => 'responsive-data-list',
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);