<?php
// FROM HASH: 4c8ced6c4e5d1a45b0828a8e86e08d58
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Manage reply bans' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . $__templater->escape($__vars['item']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped('Manage reply bans' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true) . $__templater->escape($__vars['item']['title']));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['bans'], 'empty', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['bans'])) {
			foreach ($__vars['bans'] AS $__vars['ban']) {
				$__compilerTemp2 .= '
						';
				$__compilerTemp3 = '';
				if ($__vars['ban']['reason']) {
					$__compilerTemp3 .= '
									' . $__templater->escape($__vars['ban']['reason']) . '
								';
				} else {
					$__compilerTemp3 .= '
									' . 'N/A' . '
								';
				}
				$__compilerTemp4 = '';
				if ($__vars['ban']['expiry_date']) {
					$__compilerTemp4 .= '
									' . $__templater->func('date_dynamic', array($__vars['ban']['expiry_date'], array(
					))) . '
								';
				} else {
					$__compilerTemp4 .= '
									' . 'Permanent' . '
								';
				}
				$__compilerTemp2 .= $__templater->dataRow(array(
					'rowclass' => 'dataList-row--noHover',
				), array(array(
					'_type' => 'cell',
					'html' => $__templater->func('username_link', array($__vars['ban']['User'], false, array(
				))),
				),
				array(
					'_type' => 'cell',
					'html' => '
								' . $__compilerTemp3 . '
							',
				),
				array(
					'_type' => 'cell',
					'html' => '
								' . $__compilerTemp4 . '
							',
				),
				array(
					'name' => 'delete[' . $__vars['ban']['user_id'] . ']',
					'class' => 'dataList-cell--separated dataList-cell--alt',
					'_type' => 'toggle',
					'html' => '',
				))) . '
					';
			}
		}
		$__compilerTemp1 .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'_type' => 'cell',
			'html' => 'User',
		),
		array(
			'_type' => 'cell',
			'html' => 'Reason',
		),
		array(
			'_type' => 'cell',
			'html' => 'End date',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Delete',
		))) . '
					' . $__compilerTemp2 . '
				', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
			</div>
			<h2 class="block-formSectionHeader">' . 'New reply ban' . '</h2>
			<div class="block-body">
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'ac' => 'single',
		'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'username', ), false),
	), array(
		'label' => 'Username',
		'explain' => 'This user will still be able to view this item, but will not be able to comment or rate until the ban expires.',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'ban_length',
	), array(array(
		'value' => 'permanent',
		'label' => 'Permanent',
		'_type' => 'option',
	),
	array(
		'value' => 'temporary',
		'selected' => true,
		'label' => 'Temporary' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array('
						<div class="inputGroup inputGroup--auto">
							' . $__templater->formTextBox(array(
		'name' => 'ban_length_value',
		'value' => '7',
	)) . '
							<span class="inputGroup-splitter"></span>
							' . $__templater->formSelect(array(
		'name' => 'ban_length_unit',
	), array(array(
		'value' => 'hours',
		'label' => 'Hours',
		'_type' => 'option',
	),
	array(
		'value' => 'days',
		'selected' => true,
		'label' => 'Days',
		'_type' => 'option',
	),
	array(
		'value' => 'weeks',
		'label' => 'Weeks',
		'_type' => 'option',
	),
	array(
		'value' => 'months',
		'label' => 'Months',
		'_type' => 'option',
	))) . '
						</div>
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Ban length',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'reason',
		'maxlength' => $__templater->func('max_length', array('XenAddons\\Showcase:ItemReplyBan', 'reason', ), false),
	), array(
		'label' => 'Reason',
		'explain' => 'This will be shown to the user if you choose to notify them.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'send_alert',
		'label' => 'Notify user of this action.',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/reply-bans', $__vars['item'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);