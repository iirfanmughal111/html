<?php
// FROM HASH: 01847271d52e5b76671cce56eea5ae83
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Create personal album');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_media_add_macros', 'add_form', array(
		'album' => $__vars['album'],
		'canUpload' => $__templater->method($__vars['album'], 'canUploadMedia', array()),
		'canEmbed' => $__templater->method($__vars['album'], 'canEmbedMedia', array()),
		'attachmentData' => $__vars['attachmentData'],
		'createPersonalAlbum' => true,
		'allowCreateAlbum' => true,
	), $__vars);
	return $__finalCompiled;
}
);