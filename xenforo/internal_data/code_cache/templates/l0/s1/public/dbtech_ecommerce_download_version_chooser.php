<?php
// FROM HASH: 8b8584ec11b6449be9f6eb36678ac44e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Choose version' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['download']['Product']['Category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['download']['Product']['title'])), $__templater->func('link', array('dbtech-ecommerce', $__vars['download']['Product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Releases'), $__templater->func('link', array('dbtech-ecommerce/releases', $__vars['download']['Product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false), array(
	));
	$__finalCompiled .= '


<div class="block">
	<div class="block-container">
		' . $__templater->filter($__vars['downloadOptions'], array(array('raw', array()),), true) . '
	</div>
</div>';
	return $__finalCompiled;
}
);