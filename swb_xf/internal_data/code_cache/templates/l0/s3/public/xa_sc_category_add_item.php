<?php
// FROM HASH: 3c4841c03fe2595cb9f3a057f722ead6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__vars['category']['content_term'] ? 'Add ' . $__templater->escape($__vars['category']['content_term']) . '' : 'Add item'));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['category'], 'canEditTags', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = '';
		if ($__vars['category']['min_tags']) {
			$__compilerTemp2 .= '
							' . 'This content must have at least ' . $__templater->escape($__vars['category']['min_tags']) . ' tag(s).' . '
						';
		}
		$__compilerTemp1 .= $__templater->formTokenInputRow(array(
			'name' => 'tags',
			'value' => $__vars['category']['draft_item']['tags'],
			'href' => $__templater->func('link', array('misc/tag-auto-complete', ), false),
			'min-length' => $__vars['xf']['options']['tagLength']['min'],
			'max-length' => $__vars['xf']['options']['tagLength']['max'],
			'max-tokens' => $__vars['xf']['options']['maxContentTags'],
		), array(
			'label' => 'Tags',
			'explain' => '
						' . 'Multiple tags may be separated by commas.' . '
						' . $__compilerTemp2 . '
					',
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__vars['category']['allow_author_rating'] AND (($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'setAuthorRatingOwn', )) OR $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'editAny', ))))) {
		$__compilerTemp3 .= '
				' . $__templater->formSelectRow(array(
			'name' => 'author_rating',
			'value' => $__vars['item']['author_rating'],
		), array(array(
			'value' => '0',
			'label' => 'No rating',
			'_type' => 'option',
		),
		array(
			'value' => '5',
			'label' => '5.00',
			'_type' => 'option',
		),
		array(
			'value' => '4.75',
			'label' => '4.75',
			'_type' => 'option',
		),
		array(
			'value' => '4.5',
			'label' => '4.50',
			'_type' => 'option',
		),
		array(
			'value' => '4.25',
			'label' => '4.25',
			'_type' => 'option',
		),
		array(
			'value' => '4',
			'label' => '4.00',
			'_type' => 'option',
		),
		array(
			'value' => '3.75',
			'label' => '3.75',
			'_type' => 'option',
		),
		array(
			'value' => '3.5',
			'label' => '3.50',
			'_type' => 'option',
		),
		array(
			'value' => '3.25',
			'label' => '3.25',
			'_type' => 'option',
		),
		array(
			'value' => '3',
			'label' => '3.00',
			'_type' => 'option',
		),
		array(
			'value' => '2.75',
			'label' => '2.75',
			'_type' => 'option',
		),
		array(
			'value' => '2.5',
			'label' => '2.50',
			'_type' => 'option',
		),
		array(
			'value' => '2.25',
			'label' => '2.25',
			'_type' => 'option',
		),
		array(
			'value' => '2',
			'label' => '2.00',
			'_type' => 'option',
		),
		array(
			'value' => '1.75',
			'label' => '1.75',
			'_type' => 'option',
		),
		array(
			'value' => '1.5',
			'label' => '1.50',
			'_type' => 'option',
		),
		array(
			'value' => '1.25',
			'label' => '1.25',
			'_type' => 'option',
		),
		array(
			'value' => '1',
			'label' => '1.00',
			'_type' => 'option',
		)), array(
			'label' => 'Author rating',
		)) . '

				<hr class="formRowSep" />
			';
	}
	$__compilerTemp4 = '';
	$__compilerTemp5 = '';
	$__compilerTemp5 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_items',
		'set' => $__vars['item']['custom_fields'],
		'group' => 'header',
		'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp5)) > 0) {
		$__compilerTemp4 .= '
				' . $__compilerTemp5 . '
			';
	}
	$__compilerTemp6 = '';
	$__compilerTemp7 = '';
	$__compilerTemp7 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_items',
		'set' => $__vars['item']['custom_fields'],
		'group' => 'section_1_above',
		'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp7)) > 0) {
		$__compilerTemp6 .= '
				' . $__compilerTemp7 . '
			';
	}
	$__compilerTemp8 = '';
	$__compilerTemp9 = '';
	$__compilerTemp9 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_items',
		'set' => $__vars['item']['custom_fields'],
		'group' => 'section_1_below',
		'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp9)) > 0) {
		$__compilerTemp8 .= '
				' . $__compilerTemp9 . '
			';
	}
	$__compilerTemp10 = '';
	if ($__vars['category']['title_s2']) {
		$__compilerTemp10 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 2 . '' . ': ' . $__templater->escape($__vars['category']['title_s2']) . '</div>

				';
		$__compilerTemp11 = '';
		$__compilerTemp11 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_2_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp11)) > 0) {
			$__compilerTemp10 .= '
					' . $__compilerTemp11 . '

				';
		}
		$__compilerTemp10 .= '

				';
		if ($__vars['category']['editor_s2']) {
			$__compilerTemp10 .= '
					' . $__templater->callMacro('xa_sc_item_edit_macros', 'message_section', array(
				'item' => $__vars['item'],
				'message' => $__vars['item']['message_s2_'],
				'minLength' => $__vars['item']['Category']['min_message_length_s2'],
				'section' => 's2',
				'label' => $__vars['category']['title_s2'],
				'description' => $__vars['category']['description_s2'],
			), $__vars) . '
				';
		}
		$__compilerTemp10 .= '

				';
		$__compilerTemp12 = '';
		$__compilerTemp12 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_2_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp12)) > 0) {
			$__compilerTemp10 .= '
					' . $__compilerTemp12 . '

				';
		}
		$__compilerTemp10 .= '
			';
	}
	$__compilerTemp13 = '';
	if ($__vars['category']['title_s3']) {
		$__compilerTemp13 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 3 . '' . ': ' . $__templater->escape($__vars['category']['title_s3']) . '</div>

				';
		$__compilerTemp14 = '';
		$__compilerTemp14 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_3_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp14)) > 0) {
			$__compilerTemp13 .= '
					' . $__compilerTemp14 . '
				';
		}
		$__compilerTemp13 .= '

				';
		if ($__vars['category']['editor_s3']) {
			$__compilerTemp13 .= '
					' . $__templater->callMacro('xa_sc_item_edit_macros', 'message_section', array(
				'item' => $__vars['item'],
				'message' => $__vars['item']['message_s3_'],
				'minLength' => $__vars['item']['Category']['min_message_length_s3'],
				'section' => 's3',
				'label' => $__vars['category']['title_s3'],
				'description' => $__vars['category']['description_s3'],
			), $__vars) . '
				';
		}
		$__compilerTemp13 .= '

				';
		$__compilerTemp15 = '';
		$__compilerTemp15 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_3_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp15)) > 0) {
			$__compilerTemp13 .= '
					' . $__compilerTemp15 . '
				';
		}
		$__compilerTemp13 .= '
			';
	}
	$__compilerTemp16 = '';
	if ($__vars['category']['title_s4']) {
		$__compilerTemp16 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 4 . '' . ': ' . $__templater->escape($__vars['category']['title_s4']) . '</div>

				';
		$__compilerTemp17 = '';
		$__compilerTemp17 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_4_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp17)) > 0) {
			$__compilerTemp16 .= '
					' . $__compilerTemp17 . '
				';
		}
		$__compilerTemp16 .= '

				';
		if ($__vars['category']['editor_s4']) {
			$__compilerTemp16 .= '
					' . $__templater->callMacro('xa_sc_item_edit_macros', 'message_section', array(
				'item' => $__vars['item'],
				'message' => $__vars['item']['message_s4_'],
				'minLength' => $__vars['item']['Category']['min_message_length_s4'],
				'section' => 's4',
				'label' => $__vars['category']['title_s4'],
				'description' => $__vars['category']['description_s4'],
			), $__vars) . '
				';
		}
		$__compilerTemp16 .= '

				';
		$__compilerTemp18 = '';
		$__compilerTemp18 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_4_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp18)) > 0) {
			$__compilerTemp16 .= '
					' . $__compilerTemp18 . '
				';
		}
		$__compilerTemp16 .= '
			';
	}
	$__compilerTemp19 = '';
	if ($__vars['category']['title_s5']) {
		$__compilerTemp19 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 5 . '' . ': ' . $__templater->escape($__vars['category']['title_s5']) . '</div>

				';
		$__compilerTemp20 = '';
		$__compilerTemp20 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_5_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp20)) > 0) {
			$__compilerTemp19 .= '
					' . $__compilerTemp20 . '
				';
		}
		$__compilerTemp19 .= '

				';
		if ($__vars['category']['editor_s5']) {
			$__compilerTemp19 .= '
					' . $__templater->callMacro('xa_sc_item_edit_macros', 'message_section', array(
				'item' => $__vars['item'],
				'message' => $__vars['item']['message_s5_'],
				'minLength' => $__vars['item']['Category']['min_message_length_s5'],
				'section' => 's5',
				'label' => $__vars['category']['title_s5'],
				'description' => $__vars['category']['description_s5'],
			), $__vars) . '
				';
		}
		$__compilerTemp19 .= '

				';
		$__compilerTemp21 = '';
		$__compilerTemp21 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_5_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp21)) > 0) {
			$__compilerTemp19 .= '
					' . $__compilerTemp21 . '
				';
		}
		$__compilerTemp19 .= '
			';
	}
	$__compilerTemp22 = '';
	if ($__vars['category']['title_s6']) {
		$__compilerTemp22 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 6 . '' . ': ' . $__templater->escape($__vars['category']['title_s6']) . '</div>

				';
		$__compilerTemp23 = '';
		$__compilerTemp23 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_6_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp23)) > 0) {
			$__compilerTemp22 .= '
					' . $__compilerTemp23 . '
				';
		}
		$__compilerTemp22 .= '

				';
		if ($__vars['category']['editor_s6']) {
			$__compilerTemp22 .= '
					' . $__templater->callMacro('xa_sc_item_edit_macros', 'message_section', array(
				'item' => $__vars['item'],
				'message' => $__vars['item']['message_s6_'],
				'minLength' => $__vars['item']['Category']['min_message_length_s6'],
				'section' => 's6',
				'label' => $__vars['category']['title_s6'],
				'description' => $__vars['category']['description_s6'],
			), $__vars) . '
				';
		}
		$__compilerTemp22 .= '

				';
		$__compilerTemp24 = '';
		$__compilerTemp24 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_6_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp24)) > 0) {
			$__compilerTemp22 .= '
					' . $__compilerTemp24 . '
				';
		}
		$__compilerTemp22 .= '
			';
	}
	$__compilerTemp25 = '';
	if ($__vars['category']['allow_location']) {
		$__compilerTemp25 .= '
				' . $__templater->callMacro('xa_sc_item_edit_macros', 'location', array(
			'item' => $__vars['item'],
		), $__vars) . '
			';
	}
	$__compilerTemp26 = '';
	$__compilerTemp27 = '';
	$__compilerTemp27 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit_groups', array(
		'type' => 'sc_items',
		'set' => $__vars['item']['custom_fields'],
		'groups' => array('new_tab', 'sidebar', 'new_sidebar_block', 'self_place', ),
		'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp27)) > 0) {
		$__compilerTemp26 .= '
				' . $__compilerTemp27 . '

				<hr class="formRowSep" />
			';
	}
	$__compilerTemp28 = '';
	if ($__vars['category']['allow_comments'] AND (($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'lockUnlockCommentsOwn', )) OR $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'lockUnlockCommentsAny', ))))) {
		$__compilerTemp28 .= '
				' . $__templater->formRow('
					' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'comments_open',
			'label' => 'Comments open',
			'hint' => 'When enabled, registered users or guests with appropriate permissions may comment on this item.',
			'checked' => $__vars['item'],
			'_type' => 'option',
		))) . '
				', array(
		)) . '
			';
	} else {
		$__compilerTemp28 .= '
				' . $__templater->formHiddenVal('comments_open', '1', array(
		)) . '
			';
	}
	$__compilerTemp29 = '';
	if ($__vars['category']['allow_ratings'] AND (($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'lockUnlockRatingsOwn', )) OR $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'lockUnlockRatingsAny', ))))) {
		$__compilerTemp29 .= '
				' . $__templater->formRow('
					' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'ratings_open',
			'label' => 'Ratings open',
			'hint' => 'When enabled, registered users with appropriate permissions may rate/review this item.',
			'checked' => $__vars['item'],
			'_type' => 'option',
		))) . '
				', array(
		)) . '
			';
	} else {
		$__compilerTemp29 .= '
				' . $__templater->formHiddenVal('ratings_open', '1', array(
		)) . '
			';
	}
	$__compilerTemp30 = '';
	if ($__templater->method($__vars['category'], 'canCreatePoll', array())) {
		$__compilerTemp30 .= '
			<h2 class="block-formSectionHeader">
				<span class="collapseTrigger collapseTrigger--block' . ($__vars['category']['draft_item']['poll'] ? ' is-active' : '') . '" data-xf-click="toggle" data-target="< :up :next">
					<span class="block-formSectionHeader-aligner">' . 'Post a poll' . '</span>
				</span>
			</h2>
			<div class="block-body block-body--collapsible' . ($__vars['category']['draft_item']['poll'] ? ' is-active' : '') . '">
				' . $__templater->callMacro('poll_macros', 'add_edit_inputs', array(
			'draft' => $__vars['category']['draft_item']['poll'],
		), $__vars) . '
			</div>
		';
	}
	$__compilerTemp31 = array();
	if ($__templater->isTraversable($__vars['hours'])) {
		foreach ($__vars['hours'] AS $__vars['hour']) {
			$__compilerTemp31[] = array(
				'value' => $__vars['hour'],
				'label' => $__templater->escape($__vars['hour']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp32 = array();
	if ($__templater->isTraversable($__vars['minutes'])) {
		foreach ($__vars['minutes'] AS $__vars['minute']) {
			$__compilerTemp32[] = array(
				'value' => $__vars['minute'],
				'label' => $__templater->escape($__vars['minute']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp33 = $__templater->mergeChoiceOptions(array(), $__vars['timeZones']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('xa_sc_item_edit_macros', 'title_item_add', array(
		'item' => $__vars['item'],
		'prefixes' => $__vars['prefixes'],
	), $__vars) . '

			' . $__compilerTemp1 . '

			' . $__templater->callMacro('xa_sc_item_edit_macros', 'description', array(
		'item' => $__vars['item'],
	), $__vars) . '

			' . $__compilerTemp3 . '
			
			' . $__compilerTemp4 . '

			<div class="block-formSectionHeader">' . 'Section ' . 1 . '' . ': ' . $__templater->escape($__vars['category']['title_s1']) . '</div>

			' . $__compilerTemp6 . '

			' . $__templater->callMacro('xa_sc_item_edit_macros', 'message', array(
		'item' => $__vars['item'],
		'message' => $__vars['item']['message_'],
		'minLength' => $__vars['item']['Category']['min_message_length_s1'],
		'attachmentData' => $__vars['attachmentData'],
		'label' => $__vars['category']['title_s1'],
		'description' => $__vars['category']['description_s1'],
		'showAttachmentRequired' => true,
	), $__vars) . '

			' . $__compilerTemp8 . '

			' . $__compilerTemp10 . '

			' . $__compilerTemp13 . '

			' . $__compilerTemp16 . '

			' . $__compilerTemp19 . '

			' . $__compilerTemp22 . '

			<div class="block-formSectionHeader">' . 'Additional content settings and options' . '</div>
			
			' . $__compilerTemp25 . '

			<hr class="formRowSep" />

			' . $__compilerTemp26 . '
			
			' . $__compilerTemp28 . '
			
			' . $__compilerTemp29 . '				
		</div>
		
		' . $__compilerTemp30 . '
		
		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Search engine optimization options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__templater->callMacro('xa_sc_item_edit_macros', 'og_title_meta_title', array(
		'item' => $__vars['item'],
	), $__vars) . '
			' . $__templater->callMacro('xa_sc_item_edit_macros', 'meta_description', array(
		'item' => $__vars['item'],
	), $__vars) . '
		</div>

		<hr class="formRowSep" />

		' . $__templater->formRadioRow(array(
		'name' => 'save_type',
		'value' => 'publish_now',
	), array(array(
		'value' => 'publish_now',
		'label' => '<b><font color="green">Submit for publishing now</font></b>',
		'hint' => 'Submit your item for publishing now.  <b>Note:</b> Some items require approval before being displayed publicly.',
		'_type' => 'option',
	),
	array(
		'value' => 'publish_scheduled',
		'label' => '<b><font color="orange">Schedule for publishing on a set date/time.</font></b>',
		'hint' => 'Schedule your item to be published on a set date/time.  Your scheduled items can be accessed via the "Your item >> Your items awaiting publishing page". <b>Note:</b> Some items require approval before being displayed publicly.',
		'data-hide' => 'true',
		'_dependent' => array('
					' . $__templater->formRow('
						<div class="inputGroup">
							' . $__templater->formDateInput(array(
		'name' => 'item_publish_date',
		'value' => ($__vars['xf']['time'] ? $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false) : ''),
	)) . '
							<span class="inputGroup-text">
								' . 'Time' . $__vars['xf']['language']['label_separator'] . '
							</span>
							<span class="inputGroup" dir="ltr">
								' . $__templater->formSelect(array(
		'name' => 'item_publish_hour',
		'value' => '',
		'class' => 'input--inline input--autoSize',
	), $__compilerTemp31) . '
								<span class="inputGroup-text">:</span>
								' . $__templater->formSelect(array(
		'name' => 'item_publish_minute',
		'value' => '',
		'class' => 'input--inline input--autoSize',
	), $__compilerTemp32) . '
							</span>
						</div>
					', array(
		'label' => 'Publish date',
	)) . '

					' . $__templater->formSelectRow(array(
		'name' => 'item_timezone',
		'value' => $__vars['xf']['visitor']['timezone'],
	), $__compilerTemp33, array(
	)) . '
				'),
		'_type' => 'option',
	),
	array(
		'value' => 'draft',
		'label' => '<b><font color="red">Save item as draft</font></b>',
		'hint' => '<font color="red">This option allows you to save this item as a DRAFT item and then publish it once you are finished drafting the item.  Draft Items are accessed via the "Your items >> Your draft items page"</font>',
		'wrapperclass' => 'scSaveAsDraft',
		'_type' => 'option',
	)), array(
		'label' => 'Publishing options',
		'explain' => '',
	)) . '
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/categories/add', $__vars['category'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'draft' => $__templater->func('link', array('showcase/categories/draft', $__vars['category'], ), false),
		'data-preview-url' => $__templater->func('link', array('showcase/categories/preview', $__vars['category'], ), false),
	));
	return $__finalCompiled;
}
);