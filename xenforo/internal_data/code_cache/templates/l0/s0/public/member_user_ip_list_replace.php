<?php
// FROM HASH: 78ba1a492ceec26585249ff332c638ba
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['ips'])) {
		foreach ($__vars['ips'] AS $__vars['ip']) {
			$__finalCompiled .= '
	' . $__templater->dataRow(array(
				'rowclass' => 'dataList-row--noHover',
			), array(array(
				'href' => $__templater->func('link', array('misc/ip-info', null, array('ip' => $__vars['ip']['ip'], ), ), false),
				'target' => '_blank',
				'_type' => 'cell',
				'html' => $__templater->escape($__vars['ip']['ip']),
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->filter($__vars['ip']['total'], array(array('number', array()),), true),
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->func('date_dynamic', array($__vars['ip']['first_date'], array(
			))),
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->func('date_dynamic', array($__vars['ip']['last_date'], array(
			))),
			),
			array(
				'href' => $__templater->func('link', array('members/ip-users', null, array('ip' => $__vars['ip']['ip'], ), ), false),
				'overlay' => 'true',
				'_type' => 'action',
				'html' => 'More users',
			))) . '
';
		}
	}
	return $__finalCompiled;
}
);