<?php
// FROM HASH: 1278717ab8d754f82ac6d6a80a6ccc7d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['album']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Create album' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['category']['title']));
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add media' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['category']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_media_add_macros', 'add_form', array(
		'category' => $__vars['category'],
		'album' => $__vars['album'],
		'canUpload' => $__templater->method($__vars['category'], 'canUploadMedia', array()),
		'canEmbed' => $__templater->method($__vars['category'], 'canEmbedMedia', array()),
		'attachmentData' => $__vars['attachmentData'],
		'allowCreateAlbum' => ($__vars['album'] ? true : false),
	), $__vars);
	return $__finalCompiled;
}
);