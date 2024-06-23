<?php
// FROM HASH: f5a57164be975fe406c3d18375272a3b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Showcase: Convert thread to item');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['thread'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'xf/prefix_menu.js',
		'min' => '1',
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	$__compilerTemp2 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'label' => $__templater->func('repeat_raw', array('&nbsp; ', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__templater->includeJs(array(
		'src' => 'xf/prefix_menu.js',
		'min' => '1',
	));
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['thread'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp3 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(
			'selected' => true,
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body js-prefixListenContainer">
			' . $__templater->formInfoRow('
				' . '<span style="color:red"> <b>Warning!</b> Performing this action will permanently and irreversibly modify the first post and all of its contents (attachments) upon successful conversion to an item!</span>' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
			
			' . $__templater->formSelectRow(array(
		'name' => 'target_category_id',
		'value' => '0',
		'class' => 'js-categoryList',
		'id' => 'js-categoryList',
	), $__compilerTemp1, array(
		'label' => 'Destination category',
	)) . '	

			' . $__templater->formRow('
				' . '' . '
				' . $__templater->callMacro('public:prefix_macros', 'select', array(
		'type' => 'sc_item',
		'prefixes' => $__vars['itemPrefixes'],
		'name' => 'new_item_prefix_id',
		'href' => $__templater->func('link', array('showcase/prefixes', ), false),
		'listenTo' => '#js-categoryList',
	), $__vars) . '
			', array(
		'label' => 'New item prefix',
		'rowtype' => 'input',
	)) . '
			
			' . $__templater->callMacro('tag_macros', 'edit_rows', array(
		'uneditableTags' => $__vars['uneditableTags'],
		'editableTags' => $__vars['editableTags'],
		'minTags' => $__vars['forum']['min_tags'],
		'tagList' => 'tagList--thread-' . $__vars['thread']['thread_id'],
	), $__vars) . '				

			' . $__templater->formRadioRow(array(
		'name' => 'new_item_state',
		'value' => 'visible',
	), array(array(
		'lable' => 'Visible',
		'value' => 'visible',
		'data-hide' => 'true',
		'label' => 'Visible',
		'_dependent' => array('
						' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'notify_watchers',
		'value' => '1',
		'selected' => true,
		'label' => 'Notify members watching the destination category',
		'_type' => 'option',
	))) . '
					'),
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'label' => 'Moderated',
		'_type' => 'option',
	),
	array(
		'value' => 'draft',
		'label' => 'Draft',
		'_type' => 'option',
	)), array(
		'label' => 'New item state',
		'explain' => '',
	)) . '
			
			' . $__compilerTemp3 . '
			
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to convert this thread to a showcase item?' . '
			', array(
		'rowtype' => 'confirm',
	)) . '			
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'confirm',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('threads/convert-thread-to-sc-item', $__vars['thread'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);