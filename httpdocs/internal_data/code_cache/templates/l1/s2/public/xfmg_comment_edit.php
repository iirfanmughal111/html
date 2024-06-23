<?php
// FROM HASH: 8f6309bba8704eb55ba524aa7c208184
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit comment');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['content'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['comment'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['lightbox']) {
		$__compilerTemp2 .= '
			' . $__templater->formRow('
				' . $__templater->button('', array(
			'type' => 'submit',
			'class' => 'button--primary',
			'icon' => 'save',
		), '', array(
		)) . '
				' . $__templater->button('Cancel', array(
			'class' => 'js-cancelButton',
		), '', array(
		)) . '
			', array(
			'rowtype' => 'fullWidth noLabel',
		)) . '
		';
	} else {
		$__compilerTemp2 .= '
			';
		$__compilerTemp3 = '';
		if ($__vars['quickEdit']) {
			$__compilerTemp3 .= '
						' . $__templater->button('Cancel', array(
				'class' => 'js-cancelButton',
				'icon' => 'cancel',
			), '', array(
			)) . '
					';
		}
		$__compilerTemp2 .= $__templater->formSubmitRow(array(
			'icon' => 'save',
			'sticky' => 'true',
		), array(
			'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
			'html' => '
					' . $__compilerTemp3 . '
				',
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="' . ((!$__vars['lightbox']) ? 'block-container' : '') . '">
		<div class="' . ((!$__vars['lightbox']) ? 'block-body' : '') . '">
			<span class="u-anchorTarget js-editContainer"></span>
			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['comment']['message'],
		'data-min-height' => ($__vars['lightbox'] ? 40 : 100),
		'data-preview-url' => $__templater->func('link', array(('media/' . (($__vars['content']['content_type'] == 'xfmg_media') ? 'media' : 'album')) . '-comments/preview', $__vars['content'], ), false),
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		'label' => 'Message',
	)) . '

			' . $__templater->formRow('
				' . $__templater->callMacro('helper_action', 'edit_type', array(
		'canEditSilently' => $__templater->method($__vars['comment'], 'canEditSilently', array()),
	), $__vars) . '
			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__compilerTemp2 . '
	</div>
', array(
		'action' => $__templater->func('link', array('media/comments/edit', $__vars['comment'], array('lightbox' => $__vars['lightbox'], ), ), false),
		'ajax' => 'true',
		'class' => ((!$__vars['lightbox']) ? 'block' : ''),
	));
	return $__finalCompiled;
}
);