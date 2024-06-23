<?php
// FROM HASH: a245de1990a2211a58f16415b9c5936d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('IP addresses logged for ' . $__templater->escape($__vars['user']['username']) . '');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['ips'], 'empty', array())) {
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
		),
		array(
			'_type' => 'cell',
			'html' => '&nbsp;',
		))) . '
					' . $__templater->includeTemplate('user_ip_list_replace', $__vars) . '
				', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
			</div>
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No IP logs were found for the requested user.' . '</div>
';
	}
	return $__finalCompiled;
}
);