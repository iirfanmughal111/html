<?php
// FROM HASH: e9d6c805fb99273801a2939c3cdd8df0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['showTypeFilters']) {
		$__compilerTemp1 .= '
		<div class="menu-row menu-row--separated">
			' . 'Type' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				' . $__templater->formSelect(array(
			'name' => 'type',
			'value' => $__vars['filters']['type'],
		), array(array(
			'value' => '',
			'label' => 'Any',
			'_type' => 'option',
		),
		array(
			'value' => 'image',
			'label' => 'Images',
			'_type' => 'option',
		),
		array(
			'value' => 'audio',
			'label' => 'Audio',
			'_type' => 'option',
		),
		array(
			'value' => 'video',
			'label' => 'Videos',
			'_type' => 'option',
		),
		array(
			'value' => 'embed',
			'label' => 'Embeds',
			'_type' => 'option',
		))) . '
			</div>
		</div>
	';
	}
	$__compilerTemp2 = '';
	if (!$__vars['user']) {
		$__compilerTemp2 .= '
		<div class="menu-row menu-row--separated">
			' . 'Owned by:' . '
			<div class="u-inputSpacer">
				' . $__templater->formTextBox(array(
			'name' => 'owner',
			'value' => ($__vars['ownerFilter'] ? $__vars['ownerFilter']['username'] : ''),
			'ac' => 'single',
		)) . '
			</div>
		</div>

	';
	}
	$__compilerTemp3 = '';
	if ($__vars['type'] == 'media') {
		$__compilerTemp3 .= '
				' . $__templater->formSelect(array(
			'name' => 'order',
			'value' => ($__vars['filters']['order'] ?: 'media_date'),
		), array(array(
			'value' => 'media_date',
			'label' => 'Date',
			'_type' => 'option',
		),
		array(
			'value' => 'comment_count',
			'label' => 'Comments',
			'_type' => 'option',
		),
		array(
			'value' => 'rating_weighted',
			'label' => 'Rating',
			'_type' => 'option',
		),
		array(
			'value' => 'reaction_score',
			'label' => 'Reaction score',
			'_type' => 'option',
		),
		array(
			'value' => 'view_count',
			'label' => 'Views',
			'_type' => 'option',
		))) . '
			';
	} else {
		$__compilerTemp3 .= '
				' . $__templater->formSelect(array(
			'name' => 'order',
			'value' => ($__vars['filters']['order'] ?: 'create_date'),
		), array(array(
			'value' => 'create_date',
			'label' => 'Date',
			'_type' => 'option',
		),
		array(
			'value' => 'media_count',
			'label' => 'Media count',
			'_type' => 'option',
		),
		array(
			'value' => 'comment_count',
			'label' => 'Comments',
			'_type' => 'option',
		),
		array(
			'value' => 'rating_weighted',
			'label' => 'Rating',
			'_type' => 'option',
		),
		array(
			'value' => 'reaction_score',
			'label' => 'Reaction score',
			'_type' => 'option',
		),
		array(
			'value' => 'view_count',
			'label' => 'Views',
			'_type' => 'option',
		))) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	' . '
	' . $__compilerTemp1 . '

	' . '
	' . $__compilerTemp2 . '

	' . '
	<div class="menu-row menu-row--separated">
		' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
		<div class="inputGroup u-inputSpacer">
			' . $__compilerTemp3 . '
			<span class="inputGroup-splitter"></span>
			' . $__templater->formSelect(array(
		'name' => 'direction',
		'value' => ($__vars['filters']['direction'] ?: 'desc'),
	), array(array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	),
	array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	))) . '
		</div>
	</div>

	<div class="menu-footer">
		<span class="menu-footer-controls">
			' . $__templater->button('Filter', array(
		'type' => 'submit',
		'class' => 'button--primary',
	), '', array(
	)) . '
		</span>
	</div>
	' . $__templater->formHiddenVal('apply', '1', array(
	)) . '
	' . $__templater->formHiddenVal('comment_page', $__vars['commentPage'], array(
	)) . '
', array(
		'action' => $__vars['action'],
	));
	return $__finalCompiled;
}
);