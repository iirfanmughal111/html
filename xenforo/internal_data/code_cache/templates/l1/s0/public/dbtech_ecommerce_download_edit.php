<?php
// FROM HASH: ab08d08979f7f4c761733b4fd5216db0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['download'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add download');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit download' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['download']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['download']['Product'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Releases'), $__templater->func('link', array('dbtech-ecommerce/releases', $__vars['download']['Product'], ), false), array(
	));
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['download'], 'isUpdate', array()) AND $__templater->method($__vars['download'], 'canDelete', array('soft', ))) {
		$__compilerTemp1 = '';
		if (($__vars['download']['download_type'] == 'dbtech_ecommerce_autogen') AND $__vars['xf']['app']['config']['dbtechEcommerceCacheReleases']) {
			$__compilerTemp1 .= '
		' . $__templater->button('
			' . 'Reset cache' . '
		', array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/release/reset-cache', $__vars['download'], ), false),
				'icon' => 'refresh',
				'overlay' => 'true',
			), '', array(
			)) . '
	';
		}
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__compilerTemp1 . '

	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/release/delete', $__vars['download'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_download_edit_macros', 'edit_form', array(
		'context' => 'public',
		'linkPrefix' => 'dbtech-ecommerce/release',
		'download' => $__vars['download'],
		'renderedOptions' => $__vars['renderedOptions'],
	), $__vars);
	return $__finalCompiled;
}
);