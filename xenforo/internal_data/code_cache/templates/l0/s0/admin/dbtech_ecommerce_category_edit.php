<?php
// FROM HASH: 6e3d7616d24e02e566bc228cf4b5475e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['category'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add category');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['category']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['category'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/categories/delete', $__vars['category'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
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
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['forumOptions'])) {
		foreach ($__vars['forumOptions'] AS $__vars['forum']) {
			$__compilerTemp1[] = array(
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
	$__compilerTemp2 = '';
	if (!$__templater->test($__vars['availableFields'], 'empty', array())) {
		$__compilerTemp2 .= '
				<hr class="formRowSep" />

				';
		$__compilerTemp3 = array();
		if ($__templater->isTraversable($__vars['availableFields'])) {
			foreach ($__vars['availableFields'] AS $__vars['fieldId'] => $__vars['field']) {
				$__compilerTemp3[] = array(
					'value' => $__vars['fieldId'],
					'label' => $__templater->escape($__vars['field']['title']),
					'labelclass' => ($__vars['field']['required'] ? 'u-appendAsterisk' : ''),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp2 .= $__templater->formCheckBoxRow(array(
			'name' => 'available_fields',
			'value' => $__vars['category']['field_cache'],
			'listclass' => 'field listColumns',
		), $__compilerTemp3, array(
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
		$__compilerTemp2 .= '
				<hr class="formRowSep" />

				';
		$__compilerTemp4 = '';
		if ($__vars['context'] == 'admin') {
			$__compilerTemp4 .= '<a href="' . $__templater->func('link', array('dbtech-ecommerce/fields/add', ), true) . '" target="_blank">' . 'Add field' . '</a>';
		}
		$__compilerTemp2 .= $__templater->formRow('
					' . $__templater->filter('None', array(array('parens', array()),), true) . ' ' . $__compilerTemp4 . '
				', array(
			'label' => 'Available fields',
		)) . '
			';
	}
	$__compilerTemp5 = '';
	if (!$__templater->test($__vars['availablePrefixes'], 'empty', array())) {
		$__compilerTemp5 .= '
				<hr class="formRowSep" />

				';
		$__compilerTemp6 = $__templater->mergeChoiceOptions(array(), $__vars['availablePrefixes']);
		$__compilerTemp5 .= $__templater->formCheckBoxRow(array(
			'name' => 'available_prefixes',
			'value' => $__vars['category']['prefix_cache'],
			'listclass' => 'prefix listColumns',
		), $__compilerTemp6, array(
			'label' => 'Available prefixes',
			'hint' => '
						' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '.prefix.listColumns',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '
					',
		)) . '

				' . $__templater->formCheckBoxRow(array(
			'name' => 'require_prefix',
			'value' => $__vars['category']['require_prefix'],
		), array(array(
			'value' => '1',
			'label' => 'Require users to select a prefix',
			'hint' => 'If selected, users will be required to select a prefix when creating or editing a product. This will not be enforced for moderators.',
			'_type' => 'option',
		)), array(
		)) . '

			';
	} else {
		$__compilerTemp5 .= '

				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . $__templater->filter('None', array(array('parens', array()),), true) . ' <a href="' . $__templater->func('link', array('dbtech-ecommerce/prefixes', ), true) . '" target="_blank">' . 'Add prefix' . '</a>
				', array(
			'label' => 'Available prefixes',
		)) . '

			';
	}
	$__compilerTemp7 = '';
	if ($__templater->isTraversable($__vars['category']['product_filters'])) {
		foreach ($__vars['category']['product_filters'] AS $__vars['filter'] => $__vars['text']) {
			$__compilerTemp7 .= '
						<div class="inputGroup">
							<span class="inputGroup-text dragHandle"
								  aria-label="' . $__templater->filter('Drag handle', array(array('for_attr', array()),), true) . '"></span>
							' . $__templater->formTextBox(array(
				'name' => 'product_filter[]',
				'value' => $__vars['filter'],
				'placeholder' => 'Value (A-Z, 0-9, and _ only)',
				'size' => '24',
				'maxlength' => '25',
				'dir' => 'ltr',
			)) . '
							<span class="inputGroup-splitter"></span>
							' . $__templater->formTextBox(array(
				'name' => 'product_filter_text[]',
				'value' => $__vars['text'],
				'placeholder' => 'Text',
				'size' => '24',
			)) . '
						</div>
					';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['category']['title'],
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['category']['description'],
		'rows' => '2',
		'autosize' => 'true',
		'class' => 'input--fitHeight--short',
	), array(
		'label' => 'Description',
		'explain' => 'You may use HTML',
	)) . '
			
			' . $__templater->callMacro('category_tree_macros', 'parent_category_select_row', array(
		'category' => $__vars['category'],
		'categoryTree' => $__vars['categoryTree'],
		'idKey' => 'category_id',
	), $__vars) . '

			' . $__templater->callMacro('display_order_macros', 'row', array(
		'value' => $__vars['category']['display_order'],
	), $__vars) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'always_moderate_create',
		'selected' => $__vars['category']['always_moderate_create'],
		'label' => '
					' . 'Always moderate products posted in this category' . '
				',
		'_type' => 'option',
	),
	array(
		'name' => 'always_moderate_update',
		'selected' => $__vars['category']['always_moderate_update'],
		'label' => '
					' . 'Always moderate product updates posted in this category' . '
				',
		'_type' => 'option',
	)), array(
		'explain' => 'These moderation options apply to the front-end only.',
	)) . '
			
			' . $__templater->formSelectRow(array(
		'name' => 'thread_node_id',
		'value' => $__vars['category']['thread_node_id'],
		'id' => 'js-dbtechEcommerceNewsNodeList',
	), $__compilerTemp1, array(
		'label' => 'Automatically create news thread in forum',
		'explain' => 'If selected, whenever a new product is released, a thread will be posted in this forum.',
	)) . '

			' . $__templater->formRow('
				' . '' . '
				' . $__templater->callMacro('public:prefix_macros', 'select', array(
		'type' => 'thread',
		'prefixes' => $__vars['threadPrefixes'],
		'selected' => $__vars['category']['thread_prefix_id'],
		'name' => 'thread_prefix_id',
		'href' => $__templater->func('link', array('forums/prefixes', ), false),
		'listenTo' => '#js-dbtechEcommerceNewsNodeList',
	), $__vars) . '
			', array(
		'label' => 'Automatically created news thread prefix',
		'rowtype' => 'input',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'product_update_notify',
		'value' => $__vars['category']['product_update_notify'],
	), array(array(
		'value' => 'thread',
		'label' => 'New thread',
		'_type' => 'option',
	),
	array(
		'value' => 'reply',
		'label' => 'Reply',
		'_type' => 'option',
	)), array(
		'label' => 'Product update notifications',
		'explain' => 'When an update to an existing product is released, the system can either start a new thread or reply to the parent product\'s thread.',
	)) . '

			' . $__compilerTemp2 . '
			
			' . $__compilerTemp5 . '
			
			' . $__templater->formRow('

				<div class="inputGroup-container" data-xf-init="list-sorter" data-drag-handle=".dragHandle">
					' . $__compilerTemp7 . '
					<div class="inputGroup is-undraggable js-blockDragafter" data-xf-init="field-adder"
						 data-remove-class="is-undraggable js-blockDragafter">
						<span class="inputGroup-text dragHandle"
							  aria-label="' . $__templater->filter('Drag handle', array(array('for_attr', array()),), true) . '"></span>
						' . $__templater->formTextBox(array(
		'name' => 'product_filter[]',
		'placeholder' => 'Value (A-Z, 0-9, and _ only)',
		'size' => '24',
		'maxlength' => '25',
		'data-i' => '0',
		'dir' => 'ltr',
	)) . '
						<span class="inputGroup-splitter"></span>
						' . $__templater->formTextBox(array(
		'name' => 'product_filter_text[]',
		'placeholder' => 'Text',
		'size' => '24',
		'data-i' => '0',
	)) . '
					</div>
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Available product filters',
		'explain' => 'The value represents the internal value for the filter. The text field is shown when the selection is displayed. You should not change the value field if you have saved any filtering options; if you do, those associations will be deleted.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/categories/save', $__vars['category'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);