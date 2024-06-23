<?php
// FROM HASH: 58321a4ff1e9aedd705797540a540b41
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Batch update items');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__vars['itemIds']) {
		$__compilerTemp1 .= '
					<span role="presentation" aria-hidden="true">&middot;</span>
					<a href="' . $__templater->func('link', array('xa-sc/list', null, array('criteria' => $__vars['criteria'], 'all' => true, ), ), true) . '">' . 'View or filter matches' . '</a>
				';
	}
	$__compilerTemp2 = array(array(
		'value' => '0',
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['categories'])) {
		foreach ($__vars['categories'] AS $__vars['category']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['category']['value'],
				'label' => $__templater->escape($__vars['category']['label']),
				'disabled' => $__vars['category']['disabled'],
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = '';
	if ($__vars['hasPrefixes']) {
		$__compilerTemp3 .= '
					' . 'If the selected items(s) have any prefixes applied which are not valid in the selected category, those prefixes will be removed.' . '
				';
	}
	$__compilerTemp4 = '';
	if ($__vars['prefixes']['prefixesGrouped']) {
		$__compilerTemp4 .= '
				' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'actions[apply_item_prefix]',
			'label' => 'Set a prefix',
			'_dependent' => array('
							' . $__templater->callMacro('public:prefix_macros', 'select', array(
			'prefixes' => $__vars['prefixes']['prefixesGrouped'],
			'name' => 'actions[prefix_id]',
			'type' => 'sc_item',
		), $__vars) . '
						'),
			'_type' => 'option',
		)), array(
			'explain' => 'The prefix will only be applied if it is valid for the category the item is in or is being moved to.',
		)) . '
			';
	}
	$__compilerTemp5 = '';
	if ($__vars['itemIds']) {
		$__compilerTemp5 .= '
		' . $__templater->formHiddenVal('item_ids', $__templater->filter($__vars['itemIds'], array(array('json', array()),), false), array(
		)) . '
	';
	} else {
		$__compilerTemp5 .= '
		' . $__templater->formHiddenVal('criteria', $__templater->filter($__vars['criteria'], array(array('json', array()),), false), array(
		)) . '
	';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h2 class="block-header">' . 'Update items' . '</h2>
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->filter($__vars['total'], array(array('number', array()),), true) . '
				' . $__compilerTemp1 . '
			', array(
		'label' => 'Matched items',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formSelectRow(array(
		'name' => 'actions[category_id]',
	), $__compilerTemp2, array(
		'label' => 'Move to category',
		'explain' => $__compilerTemp3,
	)) . '

			' . $__compilerTemp4 . '

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'actions[approve]',
		'value' => 'approve',
		'label' => 'Approve items',
		'_type' => 'option',
	),
	array(
		'name' => 'actions[unapprove]',
		'value' => 'unapprove',
		'label' => 'Unapprove items',
		'_type' => 'option',
	),
	array(
		'name' => 'actions[soft_delete]',
		'value' => 'soft_delete',
		'label' => 'Soft delete items',
		'_type' => 'option',
	)), array(
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'actions[lock_comments]',
		'value' => 'lock_comments',
		'label' => 'Lock comments',
		'_type' => 'option',
	),
	array(
		'name' => 'actions[unlock_comments]',
		'value' => 'unlock_comments',
		'label' => 'Unlock comments',
		'_type' => 'option',
	)), array(
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'actions[lock_ratings]',
		'value' => 'lock_ratings',
		'label' => 'Lock ratings',
		'_type' => 'option',
	),
	array(
		'name' => 'actions[unlock_ratings]',
		'value' => 'unlock_ratings',
		'label' => 'Unlock ratings',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Update items',
		'icon' => 'save',
	), array(
	)) . '
	</div>

	' . $__compilerTemp5 . '
', array(
		'action' => $__templater->func('link', array('xa-sc/batch-update/action', ), false),
		'class' => 'block',
	)) . '

';
	$__compilerTemp6 = '';
	if ($__vars['itemIds']) {
		$__compilerTemp6 .= '
		' . $__templater->formHiddenVal('item_ids', $__templater->filter($__vars['itemIds'], array(array('json', array()),), false), array(
		)) . '
	';
	} else {
		$__compilerTemp6 .= '
		' . $__templater->formHiddenVal('criteria', $__templater->filter($__vars['criteria'], array(array('json', array()),), false), array(
		)) . '
	';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h2 class="block-header">' . 'Delete items' . '</h2>
		<div class="block-body">
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'actions[delete]',
		'label' => '
					' . 'Confirm deletion of ' . $__templater->filter($__vars['total'], array(array('number', array()),), true) . ' items' . '
				',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'name' => 'confirm_delete',
		'icon' => 'delete',
	), array(
	)) . '
	</div>

	' . $__compilerTemp6 . '
', array(
		'action' => $__templater->func('link', array('xa-sc/batch-update/action', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);