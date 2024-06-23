<?php
// FROM HASH: e3a3f3cc21d471e3f490dae46960bf9f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add media' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['album']['title']));
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['album'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_media_add_macros', 'add_form', array(
		'album' => $__vars['album'],
		'canUpload' => $__templater->method($__vars['album'], 'canUploadMedia', array()),
		'canEmbed' => $__templater->method($__vars['album'], 'canEmbedMedia', array()),
		'attachmentData' => $__vars['attachmentData'],
	), $__vars);
	return $__finalCompiled;
}
);