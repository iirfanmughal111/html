<?php
// FROM HASH: f89c53f2b0e4edc161290bfab62d5fce
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
	if ($__vars['category']['category_id']) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('media-gallery/categories/delete', $__vars['category'], ), false),
			'icon' => 'delete',
			'data-xf-click' => 'overlay',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['category'], 'isEmpty', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = $__templater->mergeChoiceOptions(array(), $__vars['categoryTypes']);
		$__compilerTemp1 .= $__templater->formRadioRow(array(
			'name' => 'category_type',
			'value' => $__vars['category']['category_type'],
		), $__compilerTemp2, array(
			'label' => 'Category type',
			'explain' => 'Container categories are special categories that cannot directly contain albums or media items, but instead will display items from their child categories.',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->formRow('
					' . $__templater->escape($__vars['categoryTypes'][$__vars['category']['category_type']]) . '
					<div class="formRow-explain">' . 'The category type can only be changed when the category is empty.' . '</div>
					' . $__templater->formHiddenVal('category_type', $__vars['category']['category_type'], array(
		)) . '
				', array(
			'label' => 'Category type',
		)) . '
			';
	}
	$__compilerTemp3 = $__templater->mergeChoiceOptions(array(), $__vars['mediaTypes']);
	$__compilerTemp4 = array(array(
		'value' => '',
		'selected' => !$__vars['mirrorNodeIds'],
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp5 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp5)) {
		foreach ($__compilerTemp5 AS $__vars['treeEntry']) {
			$__compilerTemp4[] = array(
				'value' => $__vars['treeEntry']['record']['node_id'],
				'disabled' => ($__vars['treeEntry']['record']['node_type_id'] != 'Forum'),
				'label' => '
						' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']) . '
					',
				'_type' => 'option',
			);
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
	), array(
		'label' => 'Description',
		'hint' => 'You may use HTML',
	)) . '

			' . $__templater->callMacro('category_tree_macros', 'parent_category_select_row', array(
		'category' => $__vars['category'],
		'categoryTree' => $__vars['categoryTree'],
	), $__vars) . '

			' . $__templater->callMacro('display_order_macros', 'row', array(
		'value' => $__vars['category']['display_order'],
	), $__vars) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'min_tags',
		'value' => $__vars['category']['min_tags'],
		'min' => '0',
		'max' => '100',
	), array(
		'label' => 'Minimum required tags',
		'explain' => 'This allows you to require users to enter at least this many tags when adding media or editing tags on existing media.',
	)) . '

			<hr class="formRowSep" />

			' . $__compilerTemp1 . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'allowed_types',
		'listclass' => 'listColumns',
		'value' => $__vars['category']['allowed_types'],
	), $__compilerTemp3, array(
		'label' => 'Allowed media types',
		'explain' => 'The types selected above are only relevant to album and media categories.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'label' => 'Only show media added within the last X days' . $__vars['xf']['language']['label_separator'],
		'selected' => $__vars['category']['category_index_limit'] !== null,
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'category_index_limit',
		'value' => $__vars['category']['category_index_limit'],
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	)), array(
		'explain' => 'If you have a large category, your category view page may load slowly. This option will mitigate that to only show media from the last X days. If the option is checked and a value is set here it will always override the value from the global option. Set to 0 for unlimited.',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'mirror_node_ids[]',
		'value' => $__vars['mirrorNodeIds'],
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp4, array(
		'label' => 'Mirror attachments from forums',
		'explain' => '
					' . 'Media items will be automatically created for attachments posted in the selected forums. Media will be accessible to anyone that can view this category, so do not select private forums!' . '
				',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media-gallery/categories/save', $__vars['category'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);