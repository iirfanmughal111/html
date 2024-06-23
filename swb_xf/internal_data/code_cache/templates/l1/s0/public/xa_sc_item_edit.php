<?php
// FROM HASH: 89c2af93b44f173b779e0b0cedbe7c30
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__vars['category']['content_term'] ? 'Edit ' . $__templater->escape($__vars['category']['content_term']) . '' : 'Edit item'));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['category']['allow_author_rating'] AND $__templater->method($__vars['item'], 'canSetAuthorRating', array())) {
		$__compilerTemp1 .= '			
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
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_items',
		'set' => $__vars['item']['custom_fields'],
		'group' => 'header',
		'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '
				' . $__compilerTemp3 . '
			';
	}
	$__compilerTemp4 = '';
	$__compilerTemp5 = '';
	$__compilerTemp5 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_items',
		'set' => $__vars['item']['custom_fields'],
		'group' => 'section_1_above',
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
		'group' => 'section_1_below',
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
	if ($__vars['category']['title_s2']) {
		$__compilerTemp8 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 2 . '' . ': ' . $__templater->escape($__vars['category']['title_s2']) . '</div>

				';
		$__compilerTemp9 = '';
		$__compilerTemp9 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_2_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp9)) > 0) {
			$__compilerTemp8 .= '
					' . $__compilerTemp9 . '

				';
		}
		$__compilerTemp8 .= '

				';
		if ($__vars['category']['editor_s2']) {
			$__compilerTemp8 .= '
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
		$__compilerTemp8 .= '

				';
		$__compilerTemp10 = '';
		$__compilerTemp10 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_2_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp10)) > 0) {
			$__compilerTemp8 .= '
					' . $__compilerTemp10 . '

				';
		}
		$__compilerTemp8 .= '
			';
	}
	$__compilerTemp11 = '';
	if ($__vars['category']['title_s3']) {
		$__compilerTemp11 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 3 . '' . ': ' . $__templater->escape($__vars['category']['title_s3']) . '</div>

				';
		$__compilerTemp12 = '';
		$__compilerTemp12 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_3_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp12)) > 0) {
			$__compilerTemp11 .= '
					' . $__compilerTemp12 . '
				';
		}
		$__compilerTemp11 .= '

				';
		if ($__vars['category']['editor_s3']) {
			$__compilerTemp11 .= '
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
		$__compilerTemp11 .= '

				';
		$__compilerTemp13 = '';
		$__compilerTemp13 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_3_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp13)) > 0) {
			$__compilerTemp11 .= '
					' . $__compilerTemp13 . '
				';
		}
		$__compilerTemp11 .= '
			';
	}
	$__compilerTemp14 = '';
	if ($__vars['category']['title_s4']) {
		$__compilerTemp14 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 4 . '' . ': ' . $__templater->escape($__vars['category']['title_s4']) . '</div>

				';
		$__compilerTemp15 = '';
		$__compilerTemp15 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_4_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp15)) > 0) {
			$__compilerTemp14 .= '
					' . $__compilerTemp15 . '
				';
		}
		$__compilerTemp14 .= '

				';
		if ($__vars['category']['editor_s4']) {
			$__compilerTemp14 .= '
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
		$__compilerTemp14 .= '

				';
		$__compilerTemp16 = '';
		$__compilerTemp16 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_4_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp16)) > 0) {
			$__compilerTemp14 .= '
					' . $__compilerTemp16 . '
				';
		}
		$__compilerTemp14 .= '
			';
	}
	$__compilerTemp17 = '';
	if ($__vars['category']['title_s5']) {
		$__compilerTemp17 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 5 . '' . ': ' . $__templater->escape($__vars['category']['title_s5']) . '</div>

				';
		$__compilerTemp18 = '';
		$__compilerTemp18 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_5_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp18)) > 0) {
			$__compilerTemp17 .= '
					' . $__compilerTemp18 . '
				';
		}
		$__compilerTemp17 .= '

				';
		if ($__vars['category']['editor_s5']) {
			$__compilerTemp17 .= '
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
		$__compilerTemp17 .= '

				';
		$__compilerTemp19 = '';
		$__compilerTemp19 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_5_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp19)) > 0) {
			$__compilerTemp17 .= '
					' . $__compilerTemp19 . '
				';
		}
		$__compilerTemp17 .= '
			';
	}
	$__compilerTemp20 = '';
	if ($__vars['category']['title_s6']) {
		$__compilerTemp20 .= '
				<div class="block-formSectionHeader">' . 'Section ' . 6 . '' . ': ' . $__templater->escape($__vars['category']['title_s6']) . '</div>

				';
		$__compilerTemp21 = '';
		$__compilerTemp21 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_6_above',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp21)) > 0) {
			$__compilerTemp20 .= '
					' . $__compilerTemp21 . '
				';
		}
		$__compilerTemp20 .= '

				';
		if ($__vars['category']['editor_s6']) {
			$__compilerTemp20 .= '
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
		$__compilerTemp20 .= '

				';
		$__compilerTemp22 = '';
		$__compilerTemp22 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
			'type' => 'sc_items',
			'set' => $__vars['item']['custom_fields'],
			'group' => 'section_6_below',
			'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
			'onlyInclude' => $__vars['category']['field_cache'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp22)) > 0) {
			$__compilerTemp20 .= '
					' . $__compilerTemp22 . '
				';
		}
		$__compilerTemp20 .= '
			';
	}
	$__compilerTemp23 = '';
	if ($__vars['category']['allow_location']) {
		$__compilerTemp23 .= '
				' . $__templater->callMacro('xa_sc_item_edit_macros', 'location', array(
			'item' => $__vars['item'],
		), $__vars) . '

				<hr class="formRowSep" />
			';
	}
	$__compilerTemp24 = '';
	$__compilerTemp25 = '';
	$__compilerTemp25 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit_groups', array(
		'type' => 'sc_items',
		'set' => $__vars['item']['custom_fields'],
		'groups' => array('new_tab', 'sidebar', 'new_sidebar_block', 'self_place', ),
		'editMode' => $__templater->method($__vars['item'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp25)) > 0) {
		$__compilerTemp24 .= '
				' . $__compilerTemp25 . '

				<hr class="formRowSep" />
			';
	}
	$__compilerTemp26 = '';
	if ($__templater->method($__vars['item'], 'canLockUnlockComments', array()) AND $__vars['category']['allow_comments']) {
		$__compilerTemp26 .= '
				' . $__templater->formRow('
					' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'comments_open',
			'label' => 'Comments open',
			'hint' => 'When enabled, registered users or guests with appropriate permissions may comment on this item.',
			'checked' => $__vars['item']['comments_open'],
			'_type' => 'option',
		))) . '
				', array(
		)) . '

				<hr class="formRowSep" />
			';
	} else {
		$__compilerTemp26 .= '
				' . $__templater->formHiddenVal('comments_open', $__vars['item']['comments_open'], array(
		)) . '
			';
	}
	$__compilerTemp27 = '';
	if ($__templater->method($__vars['item'], 'canLockUnlockRatings', array()) AND $__vars['category']['allow_ratings']) {
		$__compilerTemp27 .= '
				' . $__templater->formRow('
					' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'ratings_open',
			'label' => 'Ratings open',
			'hint' => 'When enabled, registered users with appropriate permissions may rate/review this item.',
			'checked' => $__vars['item']['ratings_open'],
			'_type' => 'option',
		))) . '
				', array(
		)) . '

				<hr class="formRowSep" />
			';
	} else {
		$__compilerTemp27 .= '
				' . $__templater->formHiddenVal('ratings_open', $__vars['item']['ratings_open'], array(
		)) . '
			';
	}
	$__compilerTemp28 = '';
	if ($__vars['item']['item_state'] == 'visible') {
		$__compilerTemp28 .= '			
				' . $__templater->formRow('
					' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'post_as_update',
			'label' => 'Post as update',
			'hint' => 'Send Alerts and Emails to members that are watching this item. If unchecked, will save as a Silent Edit.',
			'_dependent' => array($__templater->formTextArea(array(
			'name' => 'update_message',
			'autosize' => 'true',
			'placeholder' => 'Optional update message',
		))),
			'_type' => 'option',
		))) . '
				', array(
		)) . '

				<hr class="formRowSep" />
			';
	}
	$__compilerTemp29 = '';
	if ($__templater->method($__vars['item'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp29 .= '
				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('xa_sc_item_edit_macros', 'title_item_edit', array(
		'item' => $__vars['item'],
		'prefixes' => $__vars['prefixes'],
	), $__vars) . '

			' . $__templater->callMacro('xa_sc_item_edit_macros', 'description', array(
		'item' => $__vars['item'],
	), $__vars) . '

			' . $__compilerTemp1 . '			
			
			' . $__compilerTemp2 . '

			<div class="block-formSectionHeader">' . 'Section ' . 1 . '' . ': ' . $__templater->escape($__vars['category']['title_s1']) . '</div>

			' . $__compilerTemp4 . '

			' . $__templater->callMacro('xa_sc_item_edit_macros', 'message', array(
		'item' => $__vars['item'],
		'message' => $__vars['item']['message_'],
		'minLength' => $__vars['item']['Category']['min_message_length_s1'],
		'attachmentData' => $__vars['attachmentData'],
		'showAttachmentRequired' => true,
		'label' => $__vars['category']['title_s1'],
		'description' => $__vars['category']['description_s1'],
	), $__vars) . '

			' . $__compilerTemp6 . '

			' . $__compilerTemp8 . '

			' . $__compilerTemp11 . '

			' . $__compilerTemp14 . '

			' . $__compilerTemp17 . '

			' . $__compilerTemp20 . '

			<div class="block-formSectionHeader">' . 'Additional content settings and options' . '</div>
			
			' . $__compilerTemp23 . '

			' . $__compilerTemp24 . '
			
			' . $__compilerTemp26 . '
		
			' . $__compilerTemp27 . '
			
			' . $__compilerTemp28 . '
			
			' . $__compilerTemp29 . '
		</div>

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

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/edit', $__vars['item'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-preview-url' => $__templater->func('link', array('showcase/preview', $__vars['item'], ), false),
	));
	return $__finalCompiled;
}
);