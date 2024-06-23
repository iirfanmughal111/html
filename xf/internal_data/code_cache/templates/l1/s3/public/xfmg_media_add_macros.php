<?php
// FROM HASH: cdf49692470f13141b6c1b9a2bd519a8
return array(
'macros' => array('add_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => null,
		'category' => null,
		'canUpload' => false,
		'canEmbed' => false,
		'attachmentData' => '!',
		'createPersonalAlbum' => false,
		'allowCreateAlbum' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
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
		'dev' => 'vendor/flow.js/flow.min.js, xf/attachment_manager.js',
	));
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	if ($__vars['canUpload']) {
		$__compilerTemp1 .= '
								<a href="' . $__templater->func('link', array('attachments/upload', null, array('type' => 'xfmg_media', 'context' => $__vars['attachmentData']['context'], 'hash' => $__vars['attachmentData']['hash'], ), ), true) . '"
									target="_blank" class="mediaList-inner mediaList-inner--upload js-attachmentUpload"
									data-accept=".' . $__templater->filter($__vars['attachmentData']['constraints']['extensions'], array(array('join', array(',.', )),), true) . '">
									' . 'Upload file' . '
								</a>

								' . $__templater->formHiddenVal('attachment_hash', $__vars['attachmentData']['hash'], array(
		)) . '
								' . $__templater->formHiddenVal('attachment_hash_combined', $__templater->filter(array('type' => 'xfmg_media', 'context' => $__vars['attachmentData']['context'], 'hash' => $__vars['attachmentData']['hash'], ), array(array('json', array()),), false), array(
		)) . '
							';
	} else {
		$__compilerTemp1 .= '
								<div class="js-attachmentUpload"><!-- Placeholder upload button --></div>
							';
	}
	$__compilerTemp2 = '';
	if ($__vars['canEmbed']) {
		$__compilerTemp2 .= '
							<li class="mediaList-button">
								<a href="' . $__templater->func('link', array('media/embed-media', null, array('context' => $__vars['attachmentData']['context'], 'create_album' => ($__vars['createPersonalAlbum'] ?: null), ), ), true) . '"
									class="mediaList-inner mediaList-inner--link"
									data-xf-click="overlay"
									target="_blank" rel="nofollow">
									' . 'Embed media' . '
								</a>
							</li>
						';
	}
	$__compilerTemp3 = '';
	if (!$__vars['allowCreateAlbum']) {
		$__compilerTemp3 .= '
					' . $__templater->formSubmitRow(array(
			'icon' => 'save',
		), array(
			'rowtype' => 'simple',
		)) . '
				';
	}
	$__compilerTemp4 = '';
	if ($__vars['allowCreateAlbum']) {
		$__compilerTemp4 .= '
			<div class="block">
				<div class="block-container">
					<div class="block-body">
						' . $__templater->formTextBoxRow(array(
			'name' => 'album[title]',
			'maxlength' => $__templater->func('max_length', array('XFMG:Album', 'title', ), false),
		), array(
			'label' => 'Album title',
		)) . '

						';
		$__compilerTemp5 = '';
		if ($__vars['category']) {
			$__compilerTemp5 .= '
									' . 'The album is being created inside the "' . $__templater->escape($__vars['category']['title']) . '" category. It will be visible to any member who can view this category.' . '
							';
		}
		$__compilerTemp4 .= $__templater->formTextAreaRow(array(
			'name' => 'album[description]',
			'autosize' => 'true',
			'rows' => '1',
			'data-xf-init' => 'user-mentioner',
			'maxlength' => $__templater->func('max_length', array('XFMG:Album', 'description', ), false),
		), array(
			'label' => 'Album description',
			'explain' => $__compilerTemp5,
		)) . '

						';
		if (!$__vars['category']) {
			$__compilerTemp4 .= '
							';
			if ($__templater->method($__vars['album'], 'canChangePrivacy', array())) {
				$__compilerTemp4 .= '
								' . $__templater->callMacro('xfmg_album_edit', 'change_privacy_view', array(
					'album' => $__vars['album'],
					'valueOverride' => $__vars['xf']['options']['xfmgDefaultViewPrivacy'],
				), $__vars) . '
							';
			} else {
				$__compilerTemp4 .= '
								' . $__templater->formHiddenVal('album[view_privacy]', $__vars['xf']['options']['xfmgDefaultViewPrivacy'], array(
				)) . '
							';
			}
			$__compilerTemp4 .= '
						';
		}
		$__compilerTemp4 .= '
					</div>
					' . $__templater->formSubmitRow(array(
			'icon' => 'save',
		), array(
			'rowtype' => 'simple',
		)) . '
				</div>
			</div>
			';
		if ($__vars['category']['category_id']) {
			$__compilerTemp4 .= '
				' . $__templater->formHiddenVal('category_id', $__vars['category']['category_id'], array(
			)) . '
			';
		}
		$__compilerTemp4 .= '
			' . $__templater->formHiddenVal('album_id', '0', array(
		)) . '
		';
	} else if ($__vars['category']['category_id']) {
		$__compilerTemp4 .= '
			' . $__templater->formHiddenVal('category_id', $__vars['category']['category_id'], array(
		)) . '
		';
	} else if ($__vars['album']['album_id']) {
		$__compilerTemp4 .= '
			' . $__templater->formHiddenVal('album_id', $__vars['album']['album_id'], array(
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('

		<div class="block block--mediaList">
			<div class="block-container">
				<div class="block-body block-row">
					<ul class="mediaList mediaList--buttons">
						<li class="mediaList-button' . ((!$__vars['canUpload']) ? ' is-hidden' : '') . '">
							' . $__compilerTemp1 . '
						</li>
						' . $__compilerTemp2 . '
					</ul>

					<ul class="mediaList js-mediaList u-hidden"></ul>

					' . $__templater->callMacro(null, 'added_media_template', array(
		'album' => $__vars['album'],
		'category' => $__vars['category'],
	), $__vars) . '
				</div>
				' . $__compilerTemp3 . '
			</div>
		</div>

		' . $__compilerTemp4 . '
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
	$__vars['container'] = ($__vars['category'] ?: $__vars['album']);
	$__finalCompiled .= '

	';
	$__compilerTemp6 = '';
	if (($__templater->method($__vars['container'], 'hasPermission', array('maxImageWidth', )) > 0) AND ($__templater->method($__vars['container'], 'hasPermission', array('maxImageHeight', )) <= 0)) {
		$__compilerTemp6 .= '
						<dl class="pairs pairs--justified">
							<dt>' . 'Max. width' . '</dt>
							<dd>
								' . $__templater->filter($__vars['attachmentData']['constraints']['width'], array(array('number', array()),), true) . 'px
							</dd>
						</dl>
						';
	} else if (($__templater->method($__vars['container'], 'hasPermission', array('maxImageWidth', )) <= 0) AND ($__templater->method($__vars['container'], 'hasPermission', array('maxImageHeight', )) > 0)) {
		$__compilerTemp6 .= '
						<dl class="pairs pairs--justified">
							<dt>' . 'Max. height' . '</dt>
							<dd>
								' . $__templater->filter($__vars['attachmentData']['constraints']['height'], array(array('number', array()),), true) . 'px
							</dd>
						</dl>
						';
	} else if (($__templater->method($__vars['container'], 'hasPermission', array('maxImageWidth', )) > 0) AND ($__templater->method($__vars['container'], 'hasPermission', array('maxImageHeight', )) > 0)) {
		$__compilerTemp6 .= '
						<dl class="pairs pairs--justified">
							<dt>' . 'Max. dimensions' . '</dt>
							<dd>
								' . $__templater->filter($__vars['attachmentData']['constraints']['width'], array(array('number', array()),), true) . 'px x ' . $__templater->filter($__vars['attachmentData']['constraints']['height'], array(array('number', array()),), true) . 'px
							</dd>
						</dl>
					';
	}
	$__compilerTemp7 = '';
	if ($__templater->method($__vars['container'], 'hasPermission', array('maxFileSize', )) > 0) {
		$__compilerTemp7 .= '
						<dl class="pairs pairs--justified">
							<dt>' . 'Max. file size' . '</dt>
							<dd>
								' . $__templater->filter($__vars['attachmentData']['constraints']['size'], array(array('file_size', array()),), true) . '
							</dd>
						</dl>
					';
	}
	$__compilerTemp8 = '';
	if ($__templater->method($__vars['container'], 'hasPermission', array('maxAllowedStorage', )) > 0) {
		$__compilerTemp8 .= '
						<dl class="pairs pairs--justified">
							<dt>' . 'Remaining storage' . '</dt>
							<dd>
								';
		if ($__vars['xf']['visitor']['xfmg_media_quota'] < $__vars['attachmentData']['constraints']['total']) {
			$__compilerTemp8 .= '
									<span data-xf-init="tooltip" title="' . $__templater->filter('Using ' . $__templater->filter($__vars['xf']['visitor']['xfmg_media_quota'], array(array('file_size', array()),), false) . ' of your ' . $__templater->filter($__vars['attachmentData']['constraints']['total'], array(array('file_size', array()),), false) . ' quota.', array(array('for_attr', array()),), true) . '">
										' . $__templater->filter(($__vars['attachmentData']['constraints']['total'] - $__vars['xf']['visitor']['xfmg_media_quota']), array(array('file_size', array()),), true) . '
									</span>
								';
		} else {
			$__compilerTemp8 .= '
									<span data-xf-init="tooltip" title="' . $__templater->filter('Using ' . $__templater->filter($__vars['attachmentData']['constraints']['total'], array(array('file_size', array()),), false) . ' of your ' . $__templater->filter($__vars['attachmentData']['constraints']['total'], array(array('file_size', array()),), false) . ' quota.', array(array('for_attr', array()),), true) . '">
										' . $__templater->filter(0, array(array('file_size', array()),), true) . '
									</span>
								';
		}
		$__compilerTemp8 .= '
							</dd>
						</dl>
					';
	}
	$__compilerTemp9 = '';
	if ($__templater->isTraversable($__vars['container']['allowed_types'])) {
		foreach ($__vars['container']['allowed_types'] AS $__vars['type']) {
			$__compilerTemp9 .= '
									';
			if ($__vars['type'] == 'embed') {
				$__compilerTemp9 .= '
										<span class="typesList-type typesList-type--' . $__templater->escape($__vars['type']) . '" data-xf-init="tooltip" title="' . $__templater->filter('You can embed media from various BB code media sites', array(array('for_attr', array()),), true) . '"></span>
									';
			} else {
				$__compilerTemp9 .= '
										';
				if ($__templater->func('xfmg_allowed_media', array($__vars['type'], ), false)) {
					$__compilerTemp9 .= '
											<span class="typesList-type typesList-type--' . $__templater->escape($__vars['type']) . '" data-xf-init="tooltip" title="' . $__templater->filter('Allowed extensions' . $__vars['xf']['language']['label_separator'], array(array('for_attr', array()),), true) . ' ' . $__templater->filter($__templater->func('xfmg_allowed_media', array($__vars['type'], ), false), array(array('join', array(', ', )),array('for_attr', array()),), true) . '"></span>
										';
				}
				$__compilerTemp9 .= '
									';
			}
			$__compilerTemp9 .= '
								';
		}
	}
	$__templater->modifySidebarHtml('constraintStats', '
		<div class="block">
			<div class="block-container">
				<div class="block-body block-row">
					' . $__compilerTemp6 . '

					' . $__compilerTemp7 . '

					' . $__compilerTemp8 . '

					<dl class="pairs pairs--justified">
						<dt>' . 'Allowed types' . '</dt>
						<dd>
							<ul class="typesList listInline listInline--bullet">
								' . $__compilerTemp9 . '
							</ul>
						</dd>
					</dl>
				</div>
			</div>
		</div>
	', 'replace');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'added_media_template' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '',
		'category' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<script type="text/template" class="js-mediaAddTemplate">
		<li class="js-mediaItem"
			' . $__templater->func('mustache', array('#attachment_id', 'data-attachment-id="{{attachment_id}}"', ), true) . '
			' . $__templater->func('mustache', array('#temp_media_id', 'data-temp-media-id="{{temp_media_id}}"', ), true) . '
			' . $__templater->func('mustache', array('#media_type', 'data-temp-media-type="{{media_type}}"', ), true) . '>

			<div class="contentRow">
				<span class="contentRow-figure mediaList-figure">
					' . $__templater->func('mustache', array('#thumbnail_date', '
						<a href="' . $__templater->func('mustache', array('link', ), true) . '" target="_blank"><img src="' . $__templater->func('mustache', array('temp_thumbnail_url', ), true) . '" class="js-attachmentThumb" alt="' . $__templater->func('mustache', array('title', ), true) . '" /></a>
					')) . '
					' . $__templater->func('mustache', array('^thumbnail_date', '
						<i class="mediaList-placeholder"></i>
					')) . '
				</span>
				<div class="contentRow-main">

					';
	$__vars['namePrefix'] = $__templater->preEscaped('media[' . $__templater->func('mustache', array('temp_media_id', ), true) . ']');
	$__finalCompiled .= '

					' . $__templater->func('mustache', array('^uploading', '
						' . $__templater->formTextBoxRow(array(
		'name' => $__vars['namePrefix'] . '[title]',
		'value' => $__templater->func('mustache', array('title', ), false),
		'maxlength' => $__templater->func('max_length', array('XFMG:MediaItem', 'title', ), false),
	), array(
		'rowclass' => 'mediaItem-input',
		'rowtype' => 'fullWidth noGutter',
		'label' => 'Title',
	)) . '

						' . $__templater->formTextAreaRow(array(
		'name' => $__vars['namePrefix'] . '[description]',
		'value' => $__templater->func('mustache', array('description', ), false),
		'autosize' => 'true',
		'maxlength' => $__templater->func('max_length', array('XFMG:MediaItem', 'description', ), false),
		'data-xf-init' => 'user-mentioner',
	), array(
		'rowclass' => 'mediaItem-input',
		'rowtype' => 'fullWidth noGutter',
		'label' => 'Description',
	)) . '

						<div data-xf-init="attachment-on-insert"
							data-file-row=".js-mediaItem"
							data-href="' . $__templater->func('link', array('media/add/on-insert', null, array('album_id' => $__vars['album']['album_id'], 'category_id' => $__vars['category']['category_id'], ), ), true) . '"
							data-link-data="' . $__templater->filter(array('name_prefix' => $__vars['namePrefix'], ), array(array('json', array()),), true) . '"
							style="display: none;"></div>

						' . $__templater->formHiddenVal($__vars['namePrefix'] . '[temp_media_id]', $__templater->func('mustache', array('temp_media_id', ), false), array(
	)) . '
						' . $__templater->formHiddenVal($__vars['namePrefix'] . '[media_hash]', $__templater->func('mustache', array('media_hash', ), false), array(
	)) . '
						' . $__templater->formHiddenVal($__vars['namePrefix'] . '[media_type]', $__templater->func('mustache', array('media_type', ), false), array(
	)) . '

						' . $__templater->func('mustache', array('#attachment_id', '
							' . $__templater->formHiddenVal($__vars['namePrefix'] . '[attachment_id]', $__templater->func('mustache', array('attachment_id', ), false), array(
	)) . '
						')) . '
						' . $__templater->func('mustache', array('#temp_media_embed_url', '
							' . $__templater->formHiddenVal($__vars['namePrefix'] . '[temp_media_embed_url]', $__templater->func('mustache', array('temp_media_embed_url', ), false), array(
	)) . '
						')) . '
						' . $__templater->func('mustache', array('#temp_media_tag', '
							' . $__templater->formHiddenVal($__vars['namePrefix'] . '[temp_media_tag]', $__templater->func('mustache', array('temp_media_tag', ), false), array(
	)) . '
						')) . '
					')) . '

					<span class="contentRow-extra u-jsOnly">
						' . $__templater->func('mustache', array('^uploading', '
							' . $__templater->button('
								' . 'Delete' . '
							', array(
		'class' => 'button--small js-mediaAction',
		'data-action' => 'delete',
	), '', array(
	)) . '
						')) . '

						' . $__templater->func('mustache', array('#uploading', '
							' . $__templater->button('
								' . 'Cancel' . '
							', array(
		'class' => 'button--small js-mediaAction',
		'data-action' => 'cancel',
	), '', array(
	)) . '
						')) . '
					</span>

					<div class="contentRow-title">
						' . $__templater->func('mustache', array('#uploading', '
							<span>' . $__templater->func('mustache', array('filename', ), true) . '</span>
						')) . '
					</div>

					' . $__templater->func('mustache', array('#uploading', '
						<div class="contentRow-spaced">
							<div class="mediaList-progress js-attachmentProgress"></div>
							<div class="mediaList-error js-attachmentError"></div>
						</div>
					')) . '

					' . $__templater->func('mustache', array('#requires_transcoding', '
						<div class="contentRow-spaced">
							<div class="mediaList-error js-attachmentError">
								' . 'This media needs to be processed before it can be added to the gallery. You will receive an alert once processing is finished.' . '
							</div>
						</div>
					')) . '
				</div>
			</div>
		</li>
	</script>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);