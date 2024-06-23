<?php
// FROM HASH: 32a90482b02ed5a855e236990e9a5848
return array(
'extensions' => array('start' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	
	return $__finalCompiled;
},
'before_sort' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	
	return $__finalCompiled;
},
'end' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (($__vars['updateListType'] == 'latestUpdatesList') OR ($__vars['updateListType'] == 'authorUpdatesList')) {
		$__compilerTemp1 .= '
		<div class="menu-row menu-row--separated">
			' . 'Item owned by' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				' . $__templater->formTextBox(array(
			'name' => 'item_owner',
			'value' => ($__vars['itemOwnerFilter'] ? $__vars['itemOwnerFilter']['username'] : ''),
			'ac' => 'single',
		)) . '
			</div>
		</div>	
	';
	}
	$__compilerTemp2 = '';
	if (($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'viewModerated', )) OR $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'viewDeleted', )))) {
		$__compilerTemp2 .= '
		<div class="menu-row menu-row--separated">
			' . 'Content state' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				';
		$__compilerTemp3 = array(array(
			'value' => '',
			'label' => 'Any',
			'_type' => 'option',
		)
,array(
			'value' => 'visible',
			'label' => 'Visible',
			'_type' => 'option',
		));
		if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'viewModerated', ))) {
			$__compilerTemp3[] = array(
				'value' => 'moderated',
				'label' => 'Moderated',
				'_type' => 'option',
			);
		}
		if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('xa_showcase', 'viewDeleted', ))) {
			$__compilerTemp3[] = array(
				'value' => 'deleted',
				'label' => 'Deleted',
				'_type' => 'option',
			);
		}
		$__compilerTemp2 .= $__templater->formSelect(array(
			'name' => 'state',
			'value' => $__vars['filters']['state'],
		), $__compilerTemp3) . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= $__templater->form('
	' . $__templater->renderExtension('start', $__vars, $__extensions) . '
	
	' . '
	<div class="menu-row menu-row--separated">
		' . 'Updates that mention' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'term',
		'value' => $__vars['filters']['term'],
	)) . '
		</div>
	</div>		

	' . '
	' . $__compilerTemp1 . '

	' . '
	<div class="menu-row menu-row--separated">
		' . 'Created' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'last_days',
		'value' => ($__vars['filters']['last_days'] ?: $__vars['forum']['list_date_limit_days']),
	), array(array(
		'value' => '-1',
		'label' => 'Any time',
		'_type' => 'option',
	),
	array(
		'value' => '7',
		'label' => '' . '7' . ' days',
		'_type' => 'option',
	),
	array(
		'value' => '14',
		'label' => '' . '14' . ' days',
		'_type' => 'option',
	),
	array(
		'value' => '30',
		'label' => '' . '30' . ' days',
		'_type' => 'option',
	),
	array(
		'value' => '60',
		'label' => '' . '2' . ' months',
		'_type' => 'option',
	),
	array(
		'value' => '90',
		'label' => '' . '3' . ' months',
		'_type' => 'option',
	),
	array(
		'value' => '182',
		'label' => '' . '6' . ' months',
		'_type' => 'option',
	),
	array(
		'value' => '365',
		'label' => '1 year',
		'_type' => 'option',
	))) . '
		</div>
	</div>

	' . '
	' . $__compilerTemp2 . '

	' . $__templater->renderExtension('before_sort', $__vars, $__extensions) . '

	' . '
	<div class="menu-row menu-row--separated">
		' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
		<div class="inputGroup u-inputSpacer">
			<span class="u-srOnly" id="ctrl_sort_by">' . 'Sort order' . '</span>
			' . $__templater->formSelect(array(
		'name' => 'order',
		'value' => ($__vars['filters']['order'] ?: 'update_date'),
	), array(array(
		'value' => 'update_date',
		'label' => 'Date',
		'_type' => 'option',
	),
	array(
		'value' => 'reaction_score',
		'label' => 'Reaction score',
		'_type' => 'option',
	))) . '
			<span class="inputGroup-splitter"></span>
			<span class="u-srOnly" id="ctrl_sort_direction">' . 'Sort direction' . '</span>
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

	' . $__templater->renderExtension('end', $__vars, $__extensions) . '

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
', array(
		'action' => $__vars['actionLink'],
	));
	return $__finalCompiled;
}
);