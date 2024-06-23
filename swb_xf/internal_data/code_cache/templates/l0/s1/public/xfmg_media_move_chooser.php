<?php
// FROM HASH: fd61bf9208c824c8bd7f9ed351046382
return array(
'macros' => array('move_chooser' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'categoryTree' => '!',
		'mediaItem' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = array();
	if ($__templater->method($__vars['categoryTree'], 'count', array())) {
		$__compilerTemp2 = array(array(
			'label' => '(' . 'Choose a category' . ')',
			'_type' => 'option',
		));
		$__compilerTemp3 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
		if ($__templater->isTraversable($__compilerTemp3)) {
			foreach ($__compilerTemp3 AS $__vars['treeEntry']) {
				$__vars['category'] = $__vars['treeEntry']['record'];
				if ($__vars['mediaItem']) {
					$__vars['disabled'] = (($__vars['mediaItem'] AND (!$__templater->method($__vars['mediaItem'], 'canMoveMediaTo', array($__vars['category'], )))));
				} else {
					$__vars['disabled'] = ((($__vars['category']['category_type'] == 'container') OR ($__vars['category']['category_type'] == 'album')));
				}
				$__compilerTemp2[] = array(
					'value' => $__vars['category']['category_id'],
					'disabled' => $__vars['disabled'],
					'label' => '
								' . $__templater->func('repeat_raw', array('&nbsp; ', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['category']['title']) . '
							',
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp1[] = array(
			'label' => 'Media category',
			'labelclass' => 'u-featuredText',
			'value' => 'category',
			'selected' => true,
			'data-hide' => 'true',
			'_dependent' => array('
				<div class="block-row">
					' . $__templater->formSelect(array(
			'name' => 'target_category_id',
		), $__compilerTemp2) . '
				</div>

				<div class="formRow-explain">
					' . 'Only categories which contain "Media items only" are available. To move the media item to an album use the option below.' . '
				</div>
			'),
			'_type' => 'option',
		);
	}
	$__compilerTemp4 = array();
	if ($__vars['xf']['options']['xfmgAllowPersonalAlbums']) {
		$__compilerTemp4[] = array(
			'value' => 'create',
			'label' => 'Create personal album',
			'selected' => true,
			'data-hide' => 'true',
			'_dependent' => array('
								<label>' . 'Album title' . '</label>
								' . $__templater->formTextBox(array(
			'name' => 'album[title]',
			'maxlength' => $__templater->func('max_length', array('XFMG:Album', 'title', ), false),
		)) . '

								<label>' . 'Album description' . '</label>
								' . $__templater->formTextArea(array(
			'name' => 'album[description]',
			'maxlength' => $__templater->func('max_length', array('XFMG:Album', 'description', ), false),
		)) . '
							'),
			'_type' => 'option',
		);
	}
	$__compilerTemp4[] = array(
		'value' => 'existing',
		'label' => 'Existing album',
		'data-hide' => 'true',
		'_dependent' => array('
							<label>' . 'Enter existing album URL:' . '</label>
							' . $__templater->formTextBox(array(
		'type' => 'url',
		'name' => 'album_url',
	)) . '
						'),
		'_type' => 'option',
	);
	$__compilerTemp1[] = array(
		'label' => 'Album',
		'labelclass' => 'u-featuredText',
		'value' => 'album',
		'data-hide' => 'true',
		'selected' => !$__templater->method($__vars['categoryTree'], 'count', array()),
		'_dependent' => array('
				' . $__templater->formRadio(array(
		'name' => 'album_type',
	), $__compilerTemp4) . '
			'),
		'_type' => 'option',
	);
	$__finalCompiled .= $__templater->formRadioRow(array(
		'name' => 'target_type',
	), $__compilerTemp1, array(
		'label' => 'Move media item to',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Move media item');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['mediaItem'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['mediaItem'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro(null, 'move_chooser', array(
		'categoryTree' => $__vars['categoryTree'],
		'mediaItem' => $__vars['mediaItem'],
	), $__vars) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media/move', $__vars['mediaItem'], ), false),
		'class' => 'block js-loaderBlock',
		'ajax' => 'true',
	)) . '

';
	return $__finalCompiled;
}
);