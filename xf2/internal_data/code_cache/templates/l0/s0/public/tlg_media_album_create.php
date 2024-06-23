<?php
// FROM HASH: 9125570ca8065da13ab4ca74c6d837a7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add album');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('media');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__templater->includeCss('xfmg_media_add.less');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'xfmg/media_add.js',
		'min' => '1',
	));
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'prod' => 'xf/attachment_manager-compiled.js',
		'dev' => 'vendor/flow.js/flow-compiled.js, xf/attachment_manager.js',
	));
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['album'], 'canUploadMedia', array())) {
		$__compilerTemp2 .= '
                            <a href="' . $__templater->func('link', array('attachments/upload', null, array('type' => 'xfmg_media', 'context' => $__vars['attachmentData']['context'], 'hash' => $__vars['attachmentData']['hash'], ), ), true) . '"
                               target="_blank" class="mediaList-inner mediaList-inner--upload js-attachmentUpload"
                               data-accept=".' . $__templater->filter($__vars['attachmentData']['constraints']['extensions'], array(array('join', array(',.', )),), true) . '">
                                ' . 'xfmg_upload_file' . '
                            </a>

                            ' . $__templater->formHiddenVal('attachment_hash', $__vars['attachmentData']['hash'], array(
		)) . '
                            ' . $__templater->formHiddenVal('attachment_hash_combined', $__templater->filter(array('type' => 'xfmg_media', 'context' => $__vars['attachmentData']['context'], 'hash' => $__vars['attachmentData']['hash'], ), array(array('json', array()),), false), array(
		)) . '
                            ';
	} else {
		$__compilerTemp2 .= '
                            <div class="js-attachmentUpload"><!-- Placeholder upload button --></div>
                        ';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['album'], 'canEmbedMedia', array())) {
		$__compilerTemp3 .= '
                        <li class="mediaList-button">
                            <a href="' . $__templater->func('link', array('media/embed-media', null, array('context' => $__vars['attachmentData']['context'], ), ), true) . '"
                               class="mediaList-inner mediaList-inner--link"
                               data-xf-click="overlay"
                               target="_blank">
                                ' . 'xfmg_embed_media' . '
                            </a>
                        </li>
                    ';
	}
	$__compilerTemp4 = '';
	if ($__vars['album']['album_id']) {
		$__compilerTemp4 .= '
        ' . $__templater->formHiddenVal('album_id', $__vars['album']['album_id'], array(
		)) . '
    ';
	} else {
		$__compilerTemp4 .= '
        ' . $__templater->formHiddenVal('album_id', '0', array(
		)) . '
    ';
	}
	$__finalCompiled .= $__templater->form('

    <div class="block block--mediaList">
        <div class="block-container">
            <div class="block-body block-row">
                <ul class="mediaList mediaList--buttons">
                    <li class="mediaList-button' . ((!$__templater->method($__vars['album'], 'canUploadMedia', array())) ? ' is-hidden' : '') . '">
                        ' . $__compilerTemp2 . '
                    </li>
                    ' . $__compilerTemp3 . '
                </ul>

                <ul class="mediaList js-mediaList u-hidden"></ul>

                ' . $__templater->callMacro('xfmg_media_add_macros', 'added_media_template', array(
		'album' => $__vars['album'],
		'category' => $__vars['category'],
	), $__vars) . '
            </div>
        </div>
    </div>

    <div class="block">
        <div class="block-container">
            <div class="block-body">
                ' . $__templater->formTextBoxRow(array(
		'name' => 'album[title]',
		'maxlength' => $__templater->func('max_length', array('XFMG:Album', 'title', ), false),
	), array(
		'label' => 'xfmg_album_title',
	)) . '

                ' . $__templater->formTextAreaRow(array(
		'name' => 'album[description]',
		'autosize' => 'true',
		'rows' => '1',
		'data-xf-init' => 'user-mentioner',
		'maxlength' => $__templater->func('max_length', array('XFMG:Album', 'description', ), false),
	), array(
		'label' => 'xfmg_album_description',
	)) . '
            </div>
            ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
        </div>
    </div>

    ' . $__templater->formHiddenVal('album[view_privacy]', 'public', array(
	)) . '
    ' . $__compilerTemp4 . '

    ' . $__templater->formHiddenVal('group_id', $__vars['group']['group_id'], array(
	)) . '
', array(
		'action' => $__templater->func('link', array('media/save-media', ), false),
		'ajax' => 'true',
		'data-xf-init' => 'media-manager',
		'data-media-action-url' => $__templater->func('link', array('media/add-action', ), false),
		'data-action-button' => '.js-mediaAction',
		'data-files-container' => '.js-mediaList',
		'data-upload-template' => '.js-mediaAddTemplate',
		'data-file-row' => '.js-mediaItem',
	)) . '
';
	return $__finalCompiled;
}
);