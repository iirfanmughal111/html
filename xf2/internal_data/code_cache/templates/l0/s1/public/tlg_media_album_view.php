<?php
// FROM HASH: 921a5f8a21724a5550d765eb13cab854
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('media');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__compilerTemp2 = $__vars;
	$__compilerTemp2['socialGroups_showAddMediaButton'] = $__templater->preEscaped($__templater->escape($__vars['xf']['visitor']['user_id']));
	$__compilerTemp2['tlgNoBreadcrumbs'] = $__templater->preEscaped('1');
	$__finalCompiled .= $__templater->includeTemplate('xfmg_album_view', $__compilerTemp2);
	return $__finalCompiled;
}
);