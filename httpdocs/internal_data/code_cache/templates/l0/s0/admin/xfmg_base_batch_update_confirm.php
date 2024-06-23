<?php
// FROM HASH: 785049522eccfd6d9804a329e2c142c3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (!$__vars['ids']) {
		$__compilerTemp1 .= '
					<span role="presentation" aria-hidden="true">&middot;</span>
					<a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/list', null, array('criteria' => $__vars['criteria'], 'all' => true, ), ), true) . '">' . 'View or filter matches' . '</a>
				';
	}
	$__compilerTemp2 = array(array(
		'name' => 'actions[approve]',
		'value' => 'approve',
		'label' => 'Approve',
		'_type' => 'option',
	)
,array(
		'name' => 'actions[unapprove]',
		'value' => 'unapprove',
		'label' => 'Unapprove',
		'_type' => 'option',
	)
,array(
		'name' => 'actions[soft_delete]',
		'value' => 'soft_delete',
		'label' => 'Soft delete items',
		'_type' => 'option',
	));
	if ($__vars['type'] == 'xfmg_media') {
		$__compilerTemp2[] = array(
			'name' => 'actions[add_watermark]',
			'value' => 'add_watermark',
			'label' => 'Watermark items',
			'_type' => 'option',
		);
		$__compilerTemp2[] = array(
			'name' => 'actions[remove_watermark]',
			'value' => 'remove_watermark',
			'label' => 'Unwatermark items',
			'_type' => 'option',
		);
	}
	$__compilerTemp3 = '';
	if ($__vars['ids']) {
		$__compilerTemp3 .= '
		' . $__templater->formHiddenVal('ids', $__templater->filter($__vars['ids'], array(array('json', array()),), false), array(
		)) . '
	';
	} else {
		$__compilerTemp3 .= '
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
		'label' => 'Matched',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
	), $__compilerTemp2, array(
	)) . '

		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Update items',
		'icon' => 'save',
	), array(
	)) . '
	</div>

	' . $__compilerTemp3 . '
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/action', ), false),
		'class' => 'block',
	)) . '

';
	$__compilerTemp4 = '';
	if ($__vars['ids']) {
		$__compilerTemp4 .= '
		' . $__templater->formHiddenVal('ids', $__templater->filter($__vars['ids'], array(array('json', array()),), false), array(
		)) . '
	';
	} else {
		$__compilerTemp4 .= '
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
		'label' => 'Confirm deletion of ' . $__templater->filter($__vars['total'], array(array('number', array()),), true) . ' items',
		'name' => 'actions[delete]',
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

	' . $__compilerTemp4 . '
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/action', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);