<?php
// FROM HASH: c77c1afd2993ed548cdbfaf2e20de167
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['ips'])) {
		foreach ($__vars['ips'] AS $__vars['ip']) {
			$__finalCompiled .= '
	';
			$__vars['decIp'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('XF:Ip', )), 'getDecrytedIp', array($__vars['ip']['ip'], ));
			$__finalCompiled .= '
	' . $__templater->dataRow(array(
				'rowclass' => 'dataList-row--noHover',
			), array(array(
				'href' => $__templater->func('link_type', array('public', 'misc/ip-info', null, array('ip' => $__vars['ip']['ip'], ), ), false),
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
				'href' => $__templater->func('link', array('users/ip-users', null, array('ip' => $__vars['ip']['ip'], ), ), false),
				'overlay' => 'true',
				'_type' => 'action',
				'html' => 'More users',
			),
			array(
				'label' => '&#8226;&#8226;&#8226;',
				'class' => 'dataList-cell--separated',
				'_type' => 'popup',
				'html' => '
			<div class="menu" data-menu="menu" aria-hidden="true" data-menu-builder="dataList">
				<div class="menu-content">
					<h3 class="menu-header">' . 'More options' . '</h3>
					<a href="' . $__templater->func('link', array('banning/ips/add', null, array('ip' => $__vars['ip']['ip'], ), ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'Ban' . '</a>
					<a href="' . $__templater->func('link', array('banning/discouraged-ips/add', null, array('ip' => $__vars['ip']['ip'], ), ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'Discourage' . '</a>
					<div class="js-menuBuilderTarget u-showMediumBlock"></div>
				</div>
			</div>
		',
			))) . '
';
		}
	}
	return $__finalCompiled;
}
);