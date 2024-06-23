<?php
// FROM HASH: 233e8ccc512f1ae2a427e476f565fb2b
return array(
'macros' => array('edit_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'context' => 'public',
		'linkPrefix' => '!',
		'product' => '!',
		'category' => '!',
		'forumOptions' => '!',
		'attachmentData' => '!',
		'userGroups' => '!',
		'shippingZones' => array(),
		'productOwner' => '!',
		'prefixes' => null,
		'availableFields' => null,
		'editableTags' => null,
		'uneditableTags' => null,
		'threadPrefixes' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeJs(array(
		'src' => 'xf/sort.js, vendor/dragula/dragula.js',
	));
	$__finalCompiled .= '
	';
	$__templater->includeCss('public:dragula.less');
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['product'], 'isUpdate', array()) AND $__templater->method($__vars['product'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
					' . $__templater->formRow('
						' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
					', array(
			'label' => 'Edit notice',
		)) . '
					
					<hr class="formRowSep" />
				';
	}
	$__compilerTemp2 = '';
	if ($__vars['context'] == 'admin') {
		$__compilerTemp2 .= '
					';
		if ($__templater->method($__vars['product'], 'isInsert', array())) {
			$__compilerTemp2 .= '
						' . $__templater->formTextBoxRow(array(
				'name' => 'username',
				'ac' => 'single',
				'value' => $__vars['productOwner']['username'],
			), array(
				'label' => 'Product owner',
			)) . '
					';
		} else {
			$__compilerTemp2 .= '
						' . $__templater->formRow('
							' . $__templater->escape($__vars['product']['User']['username']) . ' <a href="' . $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/reassign', $__vars['product'], ), true) . '" data-xf-click="overlay">' . 'Reassign' . '</a>
						', array(
				'label' => 'Product owner',
			)) . '
					';
		}
		$__compilerTemp2 .= '
				';
	}
	$__compilerTemp3 = '';
	if ($__vars['xf']['options']['enableTagging'] AND ($__templater->method($__vars['product'], 'canEditTags', array()) OR $__vars['product']['tags'])) {
		$__compilerTemp3 .= '
					' . $__templater->callMacro('tag_macros', 'edit_rows', array(
			'uneditableTags' => $__vars['uneditableTags'],
			'editableTags' => $__vars['editableTags'],
		), $__vars) . '
				';
	}
	$__compilerTemp4 = array(array(
		'name' => 'is_discountable',
		'value' => '1',
		'selected' => $__vars['product']['is_discountable'],
		'label' => 'Is discountable',
		'hint' => 'If no, this product\'s price cannot be reduced via automatic discounts.<br />
Coupons and sales may still be created for this product regardless of this setting.',
		'_type' => 'option',
	)
,array(
		'name' => 'is_featured',
		'value' => '1',
		'selected' => $__vars['product']['is_featured'],
		'label' => 'Is featured',
		'hint' => 'Featured products appear first in the list of products, and get a separate banner indicating it\'s featured.',
		'_type' => 'option',
	)
,array(
		'name' => 'is_listed',
		'value' => '1',
		'selected' => $__vars['product']['is_listed'],
		'label' => 'Is listed',
		'hint' => 'If this setting is un-ticked, this product will not appear in the product list on the main page or in the category view.',
		'_type' => 'option',
	));
	if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
		$__compilerTemp4[] = array(
			'name' => 'is_all_access',
			'value' => '1',
			'selected' => $__vars['product']['is_all_access'],
			'label' => 'Is part of "All Access"',
			'hint' => 'If selected, this product is part of the "All Access" feature, and users who are members of the required user group(s) can create a license for it.',
			'_type' => 'option',
		);
	}
	$__compilerTemp5 = '';
	if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
		$__compilerTemp5 .= '
					' . $__templater->callMacro(null, 'dbtech_ecommerce_helper_user_group_edit::additional_checkboxes', array(
			'label' => '"All Access" user groups',
			'id' => 'all_access_group_ids',
			'explain' => 'The user group(s) someone will have to be a member of in order to generate a license under the "All Access" feature.',
			'selectedUserGroups' => ($__vars['product']['product_id'] ? $__vars['product']['all_access_group_ids'] : array()),
		), $__vars) . '
				';
	}
	$__compilerTemp6 = '';
	if ((!$__templater->method($__vars['product'], 'isAddOn', array())) AND ((!$__templater->method($__vars['product'], 'isInsert', array())) AND ($__vars['context'] == 'admin'))) {
		$__compilerTemp6 .= ' <a href="' . $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/move', $__vars['product'], ), true) . '" data-xf-click="overlay">' . 'Move' . '</a>';
	}
	$__compilerTemp7 = '';
	if (!$__templater->method($__vars['product'], 'isAddOn', array())) {
		$__compilerTemp7 .= '
					';
		if (!$__templater->test($__vars['category']['product_filters'], 'empty', array())) {
			$__compilerTemp7 .= '
						<hr class="formRowSep" />

						';
			$__compilerTemp8 = array();
			if ($__templater->isTraversable($__vars['category']['product_filters'])) {
				foreach ($__vars['category']['product_filters'] AS $__vars['filterId'] => $__vars['filter']) {
					$__compilerTemp8[] = array(
						'value' => $__vars['filterId'],
						'label' => $__templater->escape($__vars['filter']),
						'_type' => 'option',
					);
				}
			}
			$__compilerTemp7 .= $__templater->formCheckBoxRow(array(
				'name' => 'available_filters',
				'value' => $__vars['product']['product_filters'],
				'listclass' => 'filter listColumns',
			), $__compilerTemp8, array(
				'label' => 'Available product filters',
				'explain' => 'You can choose to make this product appear in one or more filtering options via this setting.',
				'hint' => '
								' . $__templater->formCheckBox(array(
				'standalone' => 'true',
			), array(array(
				'check-all' => '.filter.listColumns',
				'label' => 'Select all',
				'_type' => 'option',
			))) . '
							',
			)) . '
					';
		} else {
			$__compilerTemp7 .= '
						<hr class="formRowSep" />

						' . $__templater->formRow('
							' . $__templater->filter('None', array(array('parens', array()),), true) . '
						', array(
				'label' => 'Available product filters',
				'explain' => 'You can choose to make this product appear in one or more filtering options via this setting.',
			)) . '
					';
		}
		$__compilerTemp7 .= '
				';
	}
	$__compilerTemp9 = '';
	$__compilerTemp10 = '';
	$__compilerTemp10 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit_groups', array(
		'type' => 'dbtechEcommerceProducts',
		'set' => $__vars['product']['product_fields'],
		'groups' => array('above_main', 'above_info', ),
		'editMode' => $__templater->method($__vars['product'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
		'namePrefix' => 'product_fields',
	), $__vars) . '
					';
	if (strlen(trim($__compilerTemp10)) > 0) {
		$__compilerTemp9 .= '
					' . $__compilerTemp10 . '

					<hr class="formRowSep" />
				';
	}
	$__compilerTemp11 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp11 .= '
							' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
						';
	}
	$__compilerTemp12 = '';
	$__compilerTemp13 = '';
	$__compilerTemp13 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit_groups', array(
		'type' => 'dbtechEcommerceProducts',
		'set' => $__vars['product']['product_fields'],
		'groups' => array('below_info', 'below_main', 'new_tab', ),
		'editMode' => $__templater->method($__vars['product'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['field_cache'],
		'namePrefix' => 'product_fields',
	), $__vars) . '
					';
	if (strlen(trim($__compilerTemp13)) > 0) {
		$__compilerTemp12 .= '
					' . $__compilerTemp13 . '
				';
	}
	$__compilerTemp14 = '';
	if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
		$__compilerTemp14 .= '
					' . $__templater->callMacro('dbtech_ecommerce_helper_user_group_edit', 'additional_checkboxes', array(
			'label' => 'Temporary additional user groups',
			'id' => 'temporary_extra_group_ids',
			'explain' => 'Puts the user in the selected groups after purchasing the product, but removes the selected groups when the license expires.',
			'selectedUserGroups' => $__vars['product']['temporary_extra_group_ids'],
		), $__vars) . '
				';
	}
	$__compilerTemp15 = '';
	if (!$__templater->test($__vars['availableFields'], 'empty', array())) {
		$__compilerTemp15 .= '
					<hr class="formRowSep" />

					';
		$__compilerTemp16 = array();
		if ($__templater->isTraversable($__vars['availableFields'])) {
			foreach ($__vars['availableFields'] AS $__vars['fieldId'] => $__vars['field']) {
				$__compilerTemp16[] = array(
					'value' => $__vars['fieldId'],
					'label' => $__templater->escape($__vars['field']['title']),
					'labelclass' => ($__vars['field']['required'] ? 'u-appendAsterisk' : ''),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp15 .= $__templater->formCheckBoxRow(array(
			'name' => 'available_fields',
			'value' => $__vars['product']['field_cache'],
			'listclass' => 'field listColumns',
		), $__compilerTemp16, array(
			'label' => 'Available fields',
			'explain' => '* Starred fields are required for new products when purchased. Other fields are optional.',
			'hint' => '
							' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '.field.listColumns',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '
						',
		)) . '
				';
	} else {
		$__compilerTemp15 .= '
					<hr class="formRowSep" />

					';
		$__compilerTemp17 = '';
		if ($__vars['context'] == 'admin') {
			$__compilerTemp17 .= '<a href="' . $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/order-fields/add', ), true) . '" target="_blank">' . 'Add field' . '</a>';
		}
		$__compilerTemp15 .= $__templater->formRow('
						' . $__templater->filter('None', array(array('parens', array()),), true) . ' ' . $__compilerTemp17 . '
					', array(
			'label' => 'Available fields',
		)) . '
				';
	}
	$__compilerTemp18 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['forumOptions'])) {
		foreach ($__vars['forumOptions'] AS $__vars['forum']) {
			$__compilerTemp18[] = array(
				'value' => $__vars['forum']['value'],
				'disabled' => $__vars['forum']['disabled'],
				'label' => $__templater->escape($__vars['forum']['label']),
				'_type' => 'option',
			);
		}
	}
	$__templater->includeJs(array(
		'src' => 'xf/prefix_menu.js',
		'min' => '1',
	));
	$__compilerTemp19 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['forumOptions'])) {
		foreach ($__vars['forumOptions'] AS $__vars['forum']) {
			$__compilerTemp19[] = array(
				'value' => $__vars['forum']['value'],
				'disabled' => $__vars['forum']['disabled'],
				'label' => $__templater->escape($__vars['forum']['label']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<div class="block-body">
				' . $__compilerTemp1 . '

				' . $__compilerTemp2 . '
				
				' . $__templater->callMacro(null, 'title', array(
		'product' => $__vars['product'],
		'prefixes' => $__vars['prefixes'],
	), $__vars) . '

				' . $__compilerTemp3 . '
				
				' . $__templater->callMacro(null, 'tagline', array(
		'product' => $__vars['product'],
	), $__vars) . '

				' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => ($__templater->method($__vars['product'], 'exists', array()) ? $__vars['product']['MasterDescription']['phrase_text'] : ''),
		'rows' => '2',
		'autosize' => 'true',
		'class' => 'input--fitHeight--short',
	), array(
		'label' => 'Description',
		'explain' => 'A short description that will be displayed above the main product information.',
	)) . '

				' . $__templater->formCheckBoxRow(array(
	), $__compilerTemp4, array(
	)) . '

				' . $__compilerTemp5 . '

				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . ($__vars['product']['Parent'] ? $__templater->escape($__vars['product']['Parent']['title']) : 'None') . '
				', array(
		'label' => 'Parent product',
	)) . '

				' . $__templater->formRow('
					' . $__templater->escape($__vars['category']['title']) . ' ' . $__compilerTemp6 . '
				', array(
		'label' => 'Category',
	)) . '

				' . $__compilerTemp7 . '

				<hr class="formRowSep" />
				
				' . $__compilerTemp9 . '				

				' . $__templater->formTokenInputRow(array(
		'name' => 'requirements',
		'value' => $__templater->filter($__vars['product']['requirements'], array(array('join', array(', ', )),), false),
	), array(
		'label' => 'Product requirements',
		'explain' => 'Multiple requirements may be separated by commas.',
	)) . '

				' . $__templater->formEditorRow(array(
		'name' => 'description_full',
		'value' => $__vars['product']['description_full'],
		'previewable' => '0',
	), array(
		'label' => 'Full product description',
		'explain' => 'This will be displayed in the product overview pane.',
	)) . '

				' . $__templater->formEditorRow(array(
		'name' => 'product_specification',
		'value' => $__vars['product']['product_specification'],
		'previewable' => '0',
	), array(
		'label' => ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array()) ? 'Feature list' : 'Product specifications'),
		'explain' => ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array()) ? 'You can provide a list of all the features in this product. This will be displayed in a separate tab on the product information page.' : 'You can provide a list of all the specifications of this product. This will be displayed in a separate tab on the product information page.'),
	)) . '

				' . $__templater->formEditorRow(array(
		'name' => 'copyright_info',
		'value' => $__vars['product']['copyright_info'],
		'previewable' => '0',
	), array(
		'label' => 'Copyright information',
		'explain' => 'If this product has any copyright information and/or visible branding, you can disclose that here.',
	)) . '

				<div data-xf-init="attachment-manager">
					' . $__templater->formRow('

						' . $__compilerTemp11 . '
					', array(
		'label' => 'Product images',
	)) . '
				</div>
				
				' . $__compilerTemp12 . '
			</div>

			<h3 class="block-formSectionHeader">
				<span class="block-formSectionHeader-aligner">' . 'Welcome email' . '</span>
			</h3>
			' . $__templater->formCheckBoxRow(array(
		'name' => 'welcome_email',
		'value' => $__vars['product']['welcome_email'],
	), array(array(
		'value' => '1',
		'label' => 'Send welcome email',
		'_type' => 'option',
	)), array(
		'explain' => 'This option will send an email to anyone who purchases the product. You can define the email below.',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'welcome_email_options[from_name]',
		'value' => ($__vars['product']['WelcomeEmail'] ? $__vars['product']['WelcomeEmail']['from_name'] : ($__vars['xf']['options']['emailSenderName'] ? $__vars['xf']['options']['emailSenderName'] : $__vars['xf']['options']['boardTitle'])),
		'maxlength' => $__templater->func('max_length', array('DBTech\\eCommerce:ProductWelcomeEmail', 'from_name', ), false),
	), array(
		'label' => 'From name',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'welcome_email_options[from_email]',
		'value' => ($__vars['product']['WelcomeEmail'] ? $__vars['product']['WelcomeEmail']['from_email'] : $__vars['xf']['options']['defaultEmailAddress']),
		'type' => 'email',
	), array(
		'label' => 'From email',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'welcome_email_options[email_title]',
		'value' => ($__vars['product']['WelcomeEmail'] ? $__vars['product']['WelcomeEmail']['email_title'] : ''),
	), array(
		'label' => 'Email title',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'welcome_email_options[email_format]',
		'value' => ($__vars['product']['WelcomeEmail'] ? $__vars['product']['WelcomeEmail']['email_format'] : 'text'),
	), array(array(
		'value' => 'text',
		'label' => 'Plain text',
		'_type' => 'option',
	),
	array(
		'value' => 'html',
		'label' => 'HTML',
		'hint' => 'Note that email clients handle HTML in widely varying ways. Be sure to test before sending HTML emails. A text version of your email will be generated by removing all HTML tags.',
		'_type' => 'option',
	)), array(
		'label' => 'Email format',
	)) . '

			' . $__templater->formCodeEditorRow(array(
		'name' => 'welcome_email_options[email_body]',
		'value' => ($__vars['product']['WelcomeEmail'] ? $__vars['product']['WelcomeEmail']['email_body'] : ''),
		'mode' => 'html',
		'data-line-wrapping' => 'true',
		'class' => 'codeEditor--autoSize codeEditor--proportional',
	), array(
		'label' => 'Email body',
		'explain' => ' ' . 'The following placeholders will be replaced in the message: {name}, {email}, {id}, {unsub}.',
	)) . '

			' . $__templater->filter($__templater->method($__vars['product'], 'renderOptions', array($__vars['context'], $__vars['linkPrefix'], )), array(array('raw', array()),), true) . '

			<h3 class="block-formSectionHeader">
				<span class="block-formSectionHeader-aligner">' . 'General options' . '</span>
			</h3>
			<div class="block-body">

				' . $__templater->callMacro('dbtech_ecommerce_helper_user_group_edit', 'additional_checkboxes', array(
		'label' => 'Permanent additional user groups',
		'id' => 'extra_group_ids',
		'explain' => 'Puts the user in the selected groups after purchasing the product.',
		'selectedUserGroups' => $__vars['product']['extra_group_ids'],
	), $__vars) . '

				' . $__compilerTemp14 . '

				' . $__compilerTemp15 . '				

				' . $__templater->formSelectRow(array(
		'name' => 'thread_node_id',
		'value' => $__vars['product']['thread_node_id'],
		'id' => 'js-dbtechEcommercePurchaseNodeList',
	), $__compilerTemp18, array(
		'label' => 'Automatically create purchase thread in forum',
		'explain' => 'If selected, whenever someone purchases this product, a thread will be posted in this forum.<br />
Only applies if one or more product fields are selected in the above option.',
	)) . '

				' . $__templater->formRow('
					' . '' . '
					' . $__templater->callMacro('prefix_macros', 'select', array(
		'type' => 'thread',
		'prefixes' => $__vars['threadPrefixes'],
		'selected' => ($__vars['product']['thread_prefix_id'] ? $__vars['product']['thread_prefix_id'] : 0),
		'name' => 'thread_prefix_id',
		'href' => $__templater->func('link_type', array($__vars['context'], 'forums/prefixes', ), false),
		'listenTo' => '#js-dbtechEcommercePurchaseNodeList',
	), $__vars) . '
				', array(
		'label' => 'Automatically created purchase thread prefix',
		'rowtype' => 'input',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formSelectRow(array(
		'name' => 'support_node_id',
		'value' => $__vars['product']['support_node_id'],
	), $__compilerTemp19, array(
		'label' => 'Support forum',
		'explain' => 'If selected, a "Get support" button will be shown on the product information page that directs users to this forum.',
	)) . '
			</div>

			' . $__templater->formHiddenVal('product_type', $__vars['product']['product_type'], array(
	)) . '
			' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
		</div>
	', array(
		'action' => $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/save', $__vars['product'], array('category_id' => $__vars['product']['product_category_id'], 'parent_product_id' => $__vars['product']['parent_product_id'], ), ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
';
	return $__finalCompiled;
}
),
'title' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
		'prefixes' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formPrefixInputRow($__vars['prefixes'], array(
		'type' => 'dbtechEcommerceProduct',
		'prefix-value' => $__vars['product']['prefix_id'],
		'textbox-value' => $__vars['product']['title'],
		'placeholder' => 'Title' . $__vars['xf']['language']['ellipsis'],
	), array(
		'label' => 'Title',
	)) . '
';
	return $__finalCompiled;
}
),
'tagline' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
		'name' => 'tagline',
		'value' => ($__templater->method($__vars['product'], 'exists', array()) ? $__vars['product']['MasterTagline']['phrase_text'] : ''),
	), array(
		'label' => 'Product tagline',
		'explain' => 'A short blob (around 140 characters) that briefly explains the product. This will be displayed in the product list.',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);